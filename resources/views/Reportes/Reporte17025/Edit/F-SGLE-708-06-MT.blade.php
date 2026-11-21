@extends('adminlte::page')

@section('title', 'F-SGLE-708-02-PT')

{{-- Estilos del formulario --}}
@push('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<style>
    table {
        width: 100%; /* Opcional: Para que ocupe todo el ancho disponible */
        border-collapse: collapse; /* Elimina los espacios entre bordes */
    }

    table th,
    table td {
        text-align: center; /* Centra el texto horizontalmente */
        vertical-align: middle; /* Centra el texto verticalmente */
        padding: 8px; /* Espaciado interno para mayor claridad */
    }

    table input {
        text-align: center; /* Centra el texto dentro de los inputs */
        box-sizing: border-box; /* Garantiza que los inputs respeten los bordes */
    }

    .image-preview {
        width: 100%;
        max-width: 200px;
        height: 200px;
        border: 1px solid #ddd;
        margin-top: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Cambia a 'contain' para que la imagen se ajuste dentro del contenedor sin recortarse */
    }

    .custom-btn {
        background-color: #198754 !important; /* Verde */
        color: white !important;
        border: none !important;
        border-radius: 5px !important;
        cursor: pointer !important;
    }

    .custom-btn:hover {
        background-color: #218838 !important; /* Verde más oscuro */
    }
    .pt-resultados-toolbar .merge-mode-toggle {
        --bs-btn-font-size: 1rem;
        --bs-btn-padding-y: 0.375rem;
        --bs-btn-padding-x: 0.75rem;
    }
    .pt-resultados-toolbar .merge-mode-toggle.btn-success {
        --bs-btn-bg: #dc3545;
        --bs-btn-border-color: #dc3545;
        --bs-btn-hover-bg: #bb2d3b;
        --bs-btn-hover-border-color: #b02a37;
        --bs-btn-active-bg: #b02a37;
        --bs-btn-active-border-color: #a52834;
    }
    #my-notification .dropdown-menu {
        max-height: 200px; /* Ajusta la altura según sea necesario */
        overflow-y: auto;
    }
    /* Selector de equipos: conserva su ubicación dentro del formulario. */
    #pt-equipos-medicion
    .pt-selector {
        width: 100%;
        min-width: 0;
        flex: 1;
        padding: 18px;
        border: 1px solid #dbe4ed;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(28,54,79,.08);
    }
    #pt-equipos-medicion
    .pt-selector > .col-form-label {
        display: block;
        text-align: left;
        font-size: 16px;
        font-weight: 700;
        color: #25445e;
        padding: 0 0 12px;
    }
    .pt-equipo-lista {
        display: grid;
        grid-template-columns: repeat(3,minmax(0,1fr));
        gap: 12px;
        max-height: 340px;
        overflow: auto;
        margin-top: 14px;
        padding: 3px;
        text-align: left;
    }
    .pt-equipo-opcion {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin: 0;
        padding: 16px 14px;
        border: 1px solid #dce5ee;
        border-radius: 10px;
        background: #f8fafc;
        cursor: pointer;
        font-weight: 400;
        min-height: 80px;
        transition: background .15s,border-color .15s,box-shadow .15s;
    }

    .pt-equipo-opcion:hover {
        background: #f0f6fd;
        border-color: #89b8df;
        box-shadow: 0 3px 9px #25445e12;
    }
    .pt-equipo-opcion.pt-selected {
        background: #eaf3ff;
        border-color: #1473bd;
        box-shadow: 0 0 0 1px #1473bd;
    }
    .pt-equipo-opcion input {
        width: 18px;
        height: 18px;
        margin-top: 2px;
        flex-shrink: 0;
        accent-color: #0869ad;
    }
    .pt-equipo-opcion strong {
        display: block;
        color: #29465e;
        font-size: 13px;
        font-weight: 600;
    }
    .pt-equipo-opcion small {
        display: block;
        color: #728396;
        font-size: 12px;
        margin-top: 7px;
        overflow-wrap: anywhere;
    }
    .pt-equipo-empty {
        grid-column: 1 / -1;
        padding: 22px 12px;
        color: #728396;
        font-size: 13px;
        text-align: center;
    }
    #pt-equipos-ayuda {
        display: block;
        text-align: left;
        color: #61758a;
        margin: 12px 0 0;
    }
    #pt-equipos-agregar {
        display: block;
        width: 100%;
        margin-top: 14px !important;
        padding: 10px 16px;
        border-radius: 8px;
        box-shadow: 0 2px 5px #1e7e3420;
    }
    #pt-equipos-agregar:disabled {
        box-shadow: none;
    }
    @media (max-width:991px) {
        .pt-equipo-lista {
            grid-template-columns: repeat(2,minmax(0,1fr));
        }
    }
    @media (max-width:575px) {
        .pt-equipo-lista {
            grid-template-columns: 1fr;
        }
    }
    .pt-equipos-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        cursor: pointer;
        color: #356695;
        font-weight: 700;
        list-style: none;
        background: #fff;
        border-color: #dce2e8 !important;
    }
    .pt-equipos-toggle::-webkit-details-marker {
        display: none;
    }
    .pt-equipos-toggle > span:not(:first-child) {
        font-size: 26px;
        line-height: 1;
        color: #C04040;
        font-weight: 700;
        padding: 6px 14px;
        background: #fbeeee;
        border-radius: 6px;
        border: 1px solid #edcccc;
    }
    .pt-selector[open] .pt-toggle-closed,
    .pt-selector:not([open]) .pt-toggle-open {
        display: none;
    }
    .pt-equipos-toggle:focus-visible {
        outline: 2px solid #80bdff;
        outline-offset: 5px;
    }
    .pt-equipos-titulo {
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }
    .pt-equipos-toggle > .pt-toggle-open,
    .pt-equipos-toggle > .pt-toggle-closed {
        margin-left: 24px;
        flex-shrink: 0;
    }
    #pt-items-tabla {
        width: 100%;
        table-layout: fixed;
    }
    #pt-items-tabla th {
        white-space: normal;
        overflow-wrap: anywhere;
        padding: 7px 4px;
        font-size: 12px;
    }
    #pt-items-tabla th:first-child {
        width: 6%;
    }
    #pt-items-tabla th:last-child {
        width: 8%;
    }
    #pt-items-tabla td {
        padding: 4px;
    }
    #pt-items-tabla input {
        width: 100%;
        min-width: 0;
        padding: 5px 3px;
        font-size: 12px;
    }
    #pt-items-tabla
    .pt-item-eliminar {
        padding: 3px 7px;
    }
    .pt-foto-visor {
        border: 0;
        border-radius: 12px;
        padding: 16px;
        width: min(96vw,1300px);
        max-width: 96vw;
        max-height: 94vh;
        background: #fff;
        box-shadow: 0 20px 70px #0005;
    }
    .pt-foto-visor::backdrop {
        background: rgba(15,28,43,.78);
    }
    .pt-foto-visor .pt-visor-cabecera {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 12px;
    }
    .pt-foto-visor img {
        display: block;
        width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    .pt-navegacion-form {
        position: fixed;
        right: 18px;
        bottom: 26px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        z-index: 1040;
    }
    .pt-navegacion-form button {
        width: 44px;
        height: 44px;
        border: 1px solid #fff;
        border-radius: 50%;
        background: #285b89;
        color: #fff;
        box-shadow: 0 3px 10px #0003;
        font-size: 25px;
        line-height: 1;
    }
    .pt-navegacion-form button:hover {
        background: #C04040;
    }
    .pt-navegacion-form button:focus-visible {
        outline: 3px solid #e6a931;
        outline-offset: 3px;
    }
    </style>
    <style>
        #F-SGLE-708-02-PT .foto-pt-tarjeta {
            border: 1px solid #007bff;
            border-radius: 10px;
            padding: 16px;
            background: #fff;
            margin: 12px 0 20px;
        }
        #F-SGLE-708-02-PT .foto-pt-cabecera {
            margin-bottom: 12px;
            color: #27466b;
            font-weight: 600;
        }
        #F-SGLE-708-02-PT
        .foto-pt-tarjeta
        .image-preview {
            width: 100%;
            max-width: none;
            height: 360px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f9fc;
            border: 1px solid #e1e7ef;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
            margin-bottom: 14px;
        }
        #F-SGLE-708-02-PT .foto-pt-tarjeta .image-preview[hidden],
        #F-SGLE-708-02-PT .foto-pt-tarjeta .image-preview:empty {
            display: none !important;
        }
        #F-SGLE-708-02-PT .foto-pt-tarjeta .image-preview img {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 358px !important;
            cursor: zoom-in;
            object-fit: contain;
            border: 0;
            padding: 0;
        }
        #F-SGLE-708-02-PT .foto-pt-tarjeta textarea {
            min-height: 76px;
            margin-bottom: 14px;
        }
        #F-SGLE-708-02-PT .foto-pt-acciones {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            border-top: 1px solid #e1e7ef;
            padding-top: 12px;
            margin-top: 12px;
        }
        #F-SGLE-708-02-PT .foto-pt-acciones .btn {
            margin: 0 !important;
        }
        #F-SGLE-708-02-PT .foto-pt-acciones [data-anotar-imagen-guardada] {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 14px;
            border-radius: 7px;
            border: 1px solid #e0a800;
            background: #ffc107;
            color: #322600;
            font-weight: 600;
            line-height: 1.3;
            box-shadow: 0 2px 4px rgba(120,85,0,.14);
        }
        #F-SGLE-708-02-PT .foto-pt-acciones [data-anotar-imagen-guardada]::before {
            content: '\270E';
            font-size: 17px;
            line-height: 1;
        }
        #F-SGLE-708-02-PT .foto-pt-acciones [data-anotar-imagen-guardada]:hover {
            background: #eeb300;
        }
        #F-SGLE-708-02-PT .foto-pt-acciones [data-anotar-imagen-guardada]:focus-visible {
            outline: 3px solid #805e00;
            outline-offset: 3px;
        }
        #F-SGLE-708-02-PT .foto-pt-reemplazo {
            flex-basis: 100%;
            font-size: 12px;
            color: #52657b;
            margin: 0;
        }
    </style>

@endpush

{{-- Contenido y captura del reporte --}}
@section('content')
@php
    [$Detalles_Generales, $Datos_Equipo] = \App\Http\Controllers\Reporte\Reporte17025\F_SGLE_708_02_PTController::adaptarDatosPt($Detalles_Generales ?? [], $Datos_Equipo ?? []);
@endphp

<br>
<br>
<br>
<br>

<h3 align="center">REPORTE DE: {{ $Prueba }}</h3>
<h3 align="center">FORMATO: {{$Nombre_Formato}}</h3>
<h4 align="center">{{$formatoNombrePersonalizado}}</h4>
<br>

<section class="content w-100">
    <div class="card w-100 p-3">
        <div class="card-body  w-100">
            <form id="F-SGLE-708-02-PT" data-anotaciones-fotos data-modo="edit" action="{{ route('Reportes_F_SGLE_708_02_PT.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong>No se pudo guardar el reporte:</strong>
                        <ul class="mb-0">@foreach($errors->all() as $errorPt)<li>{{ $errorPt }}</li>@endforeach</ul>
                    </div>
                @endif

                @csrf
                @include('Reportes.PINS.partials.serie-reportes', ['formatoSeriePnd' => 'F-SGLE-708-02-PT'])

                <div class="row">
                <button id="preFormBtn" type="button" class="btn btn-warning custom-btn my-2">Rellenar Campos Vacios "---"</button>
                <div style="margin-bottom: 2px;"></div>
                <!--***************************************** DATOS DEL CLIENTE *****************************************-->
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS DEL CLIENTE</div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">No. Reporte</label>
                            <input type="text" class="form-control  inputForm @error('No_Reporte') is-invalid @enderror"
                                name="Detalles_Generales[No_Reporte]" 
                                placeholder="Ejemplo: 077-8DUCTOS-24"
                                value="{{old('Detalles_Generales.No_Reporte', $Detalles_Generales['No_Reporte'] ?? '')}}">
                            @error('No_Reporte')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">
                                ¿Cliente existente?
                                <span class="ml-3">
                                    <label class="mr-2">
                                        <input type="radio" name="TieneCliente"
                                            value="si" @checked(old('TieneCliente', $Clientes->contains('Cliente', $Detalles_Generales['Cliente'] ?? '') ? 'si' : 'no') === 'si')> Sí
                                    </label>
                                    <label>
                                        <input type="radio" name="TieneCliente"
                                            value="no" @checked(old('TieneCliente', $Clientes->contains('Cliente', $Detalles_Generales['Cliente'] ?? '') ? 'si' : 'no') === 'no')> No
                                    </label>
                                </span>
                            </label>

                            <!-- SELECT cuando es SI -->
                            <select id="campoClienteSelect"
                                    class="form-select"
                                    name="ClienteSelect">
                                <option value="" selected disabled>Seleccione un Cliente</option>
                                @foreach($Clientes as $Cliente)
                                    <option value="{{ $Cliente->Cliente }}" @selected(old('ClienteSelect', $Detalles_Generales['Cliente'] ?? '') === $Cliente->Cliente)>
                                        {{ $Cliente->Cliente }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- INPUT cuando es NO -->
                            <input type="text" id="campoClienteInput"
                                class="form-control inputForm mt-2"
                                name="ClienteInput"
                                value="{{ old('ClienteInput', $Detalles_Generales['Cliente'] ?? '') }}"
                                placeholder="Ingrese nombre del cliente" style="display:none;">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Dirección:</label>
                            <input type="text" class="form-control  inputForm @error('Direccion') is-invalid @enderror"
                                name="Detalles_Generales[Direccion]" 
                                placeholder="EJE: AV. MORELOS..."
                                value="{{old('Detalles_Generales.Direccion', $Detalles_Generales['Direccion'] ?? '')}}">
                            @error('Direccion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Contacto del Cliente:</label>
                            <input type="text" class="form-control  inputForm @error('Contacto') is-invalid @enderror"
                                name="Detalles_Generales[Contacto]" 
                                placeholder="Ejemplo: 55 784 ..."
                                value="{{old('Detalles_Generales.Contacto', $Detalles_Generales['Contacto'] ?? '')}}">
                            @error('Contacto')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Orden_Trabajo]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . .">{{old('Detalles_Generales.Orden_Trabajo', $Detalles_Generales['Orden_Trabajo'] ?? '')}}</textarea>
                            @error('Orden_Trabajo')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Proyecto:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Proyecto]" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{old('Detalles_Generales.Proyecto', $Detalles_Generales['Proyecto'] ?? '')}}</textarea>
                            @error('Proyecto')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Trazabilidad del Cliente:</label>
                            <input type="text" class="form-control  inputForm @error('Trazabilidad') is-invalid @enderror"
                                name="Detalles_Generales[Trazabilidad]" 
                                placeholder="Ejemplo: ASTM...  "
                                value="{{old('Detalles_Generales.Trazabilidad', $Detalles_Generales['Trazabilidad'] ?? '')}}">
                            @error('Trazabilidad')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Partida:</label>
                            <input type="text" class="form-control  inputForm @error('Partida') is-invalid @enderror"
                                name="Detalles_Generales[Partida]" 
                                placeholder="Ejemplo: HABILITADO DE SPOOLES..."
                                value="{{old('Detalles_Generales.Partida', $Detalles_Generales['Partida'] ?? '')}}">
                            @error('Partida')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>
                    
                    <!--***************************************** FECHAS*****************************************-->
                    <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">FECHAS</div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Fecha de Recepción:</label>
                            <input type="date" class="form-control  inputForm @error('Fecha') is-invalid @enderror"
                                name="Detalles_Generales[Fecha_Recepcion]" 
                                placeholder="Ejemplo: DD/MM/AAAA"
                                value="{{old('Detalles_Generales.Fecha_Recepcion', $Detalles_Generales['Fecha_Recepcion'] ?? '')}}">
                            @error('Fecha_Recepcion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Fecha de Ensayo:</label>
                            <input type="date" class="form-control  inputForm @error('Fecha_Ensayo') is-invalid @enderror"
                                name="Detalles_Generales[Fecha_Ensayo]" 
                                placeholder="Ejemplo: DD/MM/AAAA"
                                value="{{old('Detalles_Generales.Fecha_Ensayo', $Detalles_Generales['Fecha_Ensayo'] ?? '')}}">
                            @error('Fecha_Ensayo')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Fecha de Emsión:</label>
                            <input type="date" class="form-control  inputForm @error('Fecha_Emision') is-invalid @enderror"
                                name="Detalles_Generales[Fecha_Emision]" 
                                placeholder="Ejemplo: DD/MM/AAAA"
                                value="{{old('Detalles_Generales.Fecha_Emision', $Detalles_Generales['Fecha_Emision'] ?? '')}}">
                            @error('Fecha_Emision')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <!--***************************************** DATOS AICO  *****************************************-->
                    <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS AICO</div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">
                                ¿Contrato existente?
                                <span class="ml-3">
                                    <label class="mr-2">
                                        <input type="radio" name="TieneContrato" value="si" checked> Sí
                                    </label>
                                    <label>
                                        <input type="radio" name="TieneContrato" value="no"> No
                                    </label>
                                </span>
                            </label>

                            <!-- Input visible solo si es "SI" -->
                            <input type="text" id="campoContrato"
                                class="form-control inputForm"
                                name="Detalles_Generales[Contrato]"
                                placeholder="Ejemplo: 640853841"
                                value="{{ old('Detalles_Generales.Contrato', $Detalles_Generales['Contrato'] ?? '') }}" required>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo AICO:</label>
                            <input type="text" class="form-control  inputForm @error('OT_AICO') is-invalid @enderror"
                                name="Detalles_Generales[OT_AICO]" 
                                placeholder="Ejemplo: PT-SI-26"
                                value="{{old('Detalles_Generales.OT_AICO', $Detalles_Generales['OT_AICO'] ?? '')}}">
                            @error('OT_AICO')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <!--***************************************** EQUIPOS DE MEDICIÓN UTILIZADOS  *****************************************-->
                    <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">EQUIPOS DE MEDICIÓN UTILIZADOS</div>
                    @php
                        $equiposPt = collect($idsGeneral_EyCs_Equipos ?? [])->where('Tipo', 'EQUIPOS')->unique('idGeneral_EyC');
                        $certificadosPt = \App\Models\EquiposyConsumibles\certificados::whereIn('idGeneral_EyC', $equiposPt->pluck('idGeneral_EyC'))->get()->keyBy('idGeneral_EyC');
                        $filasPt = old('Datos_Equipo') !== null
                            ? old('Datos_Equipo.EQUIPOS_MEDICION', [])
                            : array_merge(...\App\Http\Controllers\Reporte\Reporte17025\F_SGLE_708_02_PTController::paginasEquiposMedicionPt($Datos_Equipo ?? []));
                        $catalogoPt = $equiposPt->map(fn($e) => ['ID' => (string)$e->idGeneral_EyC, 'DESCRIPCION' => $e->No_economico, 'MARCA' => $e->Marca, 'MODELO' => $e->Modelo, 'NO_SERIE' => $e->Serie, 'NO_CERTIFICADO' => $certificadosPt->get($e->idGeneral_EyC)?->No_certificado])->values();
                    @endphp
                        <div class="col-12 mb-3" id="pt-equipos-medicion">
                        <div class="d-flex justify-content-center mb-3">
                            <details class="form-group text-center pt-selector w-100" open>
                                <summary class="pt-equipos-toggle btn btn-light border w-100 p-3 mb-2" style="display:flex; align-items:center; justify-content:space-between; gap:24px;"><span class="pt-equipos-titulo"><i class="fas fa-toolbox" aria-hidden="true"></i><span>Equipos</span></span><span class="pt-toggle-open" aria-label="Ocultar equipos" title="Ocultar equipos">▴</span><span class="pt-toggle-closed" aria-label="Mostrar equipos" title="Mostrar equipos">▾</span></summary>

                                <select id="pt-equipo-catalogo" class="form-select" multiple size="6">
                                    
                                    @foreach($equiposPt->sortBy('Nombre_E_P_BP') as $equipo)
                                        <option value="{{ $equipo->idGeneral_EyC }}">{{ $equipo->Nombre_E_P_BP }} — {{ $equipo->No_economico }} — {{ $equipo->Serie }}</option>
                                    @endforeach
                                </select><button type="button" class="btn btn-success mt-2" id="pt-equipos-agregar">Agregar equipos seleccionados</button>
                            </details>
                        </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm mb-0">
                                <thead><tr>
                                    @foreach(['Descripción', 'Marca', 'Modelo', 'N.º de serie', 'Trazabilidad', 'Referencia', 'N.º de certificado', 'Eliminar'] as $titulo)
                                        <th scope="col" class="text-center align-middle">{{ $titulo }}</th>
                                    @endforeach
                                </tr></thead>
                                <tbody id="pt-equipos-filas"></tbody>
                            </table>
                        </div>
                    </div>


                     <!--***************************************** CONDICIONES DE LA PRUEBA  *****************************************-->
                    <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">CONDICIONES DE LA PRUEBA</div>
                
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Lugar de Ensayo:</label>
                            <input type="text" class="form-control  inputForm @error('Lugar') is-invalid @enderror"
                                name="Detalles_Generales[Lugar]" 
                                placeholder="Ejemplo:  "
                                value="{{old('Detalles_Generales.Lugar', $Detalles_Generales['Lugar'] ?? '')}}">
                            @error('Lugar')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Tipo de Material:</label>
                            <input type="text" class="form-control  inputForm @error('Material') is-invalid @enderror"
                                name="Detalles_Generales[Material]" 
                                placeholder="Ejemplo: ACERO... "
                                value="{{old('Detalles_Generales.Material', $Detalles_Generales['Material'] ?? '')}}">
                            @error('Material')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Tipo de Luz:</label>
                            <input type="text" class="form-control  inputForm @error('Tipo_luz') is-invalid @enderror"
                                name="Detalles_Generales[Tipo_luz]" 
                                placeholder="Ejemplo: BLANCA.. "
                                value="{{old('Detalles_Generales.Tipo_luz', $Detalles_Generales['Tipo_luz'] ?? '')}}">
                            @error('Tipo_luz')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Intensidad:</label>
                            <input type="text" class="form-control  inputForm @error('Intensidad') is-invalid @enderror"
                                name="Detalles_Generales[Intensidad]" 
                                placeholder="Ejemplo: 9031 "
                                value="{{old('Detalles_Generales.Intensidad', $Detalles_Generales['Intensidad'] ?? '')}}">
                            @error('Intensidad')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Condición de la Superficie:</label>
                            <input type="text" class="form-control  inputForm @error('Condicion_superficie') is-invalid @enderror"
                                name="Detalles_Generales[Condicion_superficie]" 
                                placeholder="Ejemplo: LISA... "
                                value="{{old('Detalles_Generales.Condicion_superficie', $Detalles_Generales['Condicion_superficie'] ?? '')}}">
                            @error('Condicion_superficie')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Temperatura Ambiental:</label>
                            <input type="text" class="form-control  inputForm @error('Temperatura_ambiental') is-invalid @enderror"
                                name="Detalles_Generales[Temperatura_ambiental]" 
                                placeholder="Ejemplo: 21.5 "
                                value="{{old('Detalles_Generales.Temperatura_ambiental', $Detalles_Generales['Temperatura_ambiental'] ?? '')}}">
                            @error('Temperatura_ambiental')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Irradiancia:</label>
                            <input type="text" class="form-control  inputForm @error('Irradiancia') is-invalid @enderror"
                                name="Detalles_Generales[Irradiancia]" 
                                placeholder="Ejemplo: ... "
                                value="{{old('Detalles_Generales.Irradiancia', $Detalles_Generales['Irradiancia'] ?? '')}}">
                            @error('Irradiancia')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Temperatura de la Superficie del Material:</label>
                            <input type="text" class="form-control  inputForm @error('Temperatura_material') is-invalid @enderror"
                                name="Detalles_Generales[Temperatura_material]" 
                                placeholder="Ejemplo: 21.4 "
                                value="{{old('Detalles_Generales.Temperatura_material', $Detalles_Generales['Temperatura_material'] ?? '')}}">
                            @error('Temperatura_material')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                     <!--***************************************** REFERENCIA Y MÉTODO  *****************************************-->
                    <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">REFERENCIA Y MÉTODO</div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Procedimiento</label>
                            <input type="text" class="form-control  inputForm @error('Procedimiento') is-invalid @enderror"
                                name="Detalles_Generales[Procedimiento]" 
                                placeholder="Ejemplo:  "
                                value="{{ old('Detalles_Generales.Procedimiento', $Detalles_Generales['Procedimiento'] ?? '') }}" readonly>
                            @error('Procedimiento')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Ensayo Realizado:</label>
                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Ensayo_realizado]" placeholder="Ejemplo: LIQUIDOS PENETRANTES . . .">{{old('Detalles_Generales.Ensayo_realizado', $Detalles_Generales['Ensayo_realizado'] ?? '')}}</textarea>
                            @error('Ensayo_realizado')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Tipo y Método:</label>
                            <input type="text" class="form-control  inputForm @error('Tipo_metodo') is-invalid @enderror"
                                name="Detalles_Generales[Tipo_metodo]" 
                                placeholder="Ejemplo: TIPO 2 . . .  "
                                value="{{old('Detalles_Generales.Tipo_metodo', $Detalles_Generales['Tipo_metodo'] ?? '')}}">
                            @error('Tipo_metodo')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Código y/o Estadar Utilizado:</label>
                            <input type="text" class="form-control  inputForm @error('Codigo_estandar') is-invalid @enderror"
                                name="Detalles_Generales[Codigo_estandar]" 
                                placeholder="Ejemplo: ASME . . . "
                                value="{{old('Detalles_Generales.Codigo_estandar', $Detalles_Generales['Codigo_estandar'] ?? '')}}">
                            @error('Codigo_estandar')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Criterio de Aceptación:</label>
                            <input type="text" class="form-control  inputForm @error('Criterio_aceptacion') is-invalid @enderror"
                                name="Detalles_Generales[Criterio_aceptacion]" 
                                placeholder="Ejemplo: ASME . . .   "
                                value="{{old('Detalles_Generales.Criterio_aceptacion', $Detalles_Generales['Criterio_aceptacion'] ?? '')}}">
                            @error('Criterio_aceptacion')
                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="hidden" class="form-control  inputForm " name="Detalles_Generales[idSolicitud]" value="{{ $idSolicitud }}" readonly>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="hidden" class="form-control  inputForm " name="idPrueba_Aplica" value="{{ $idPrueba_Aplica }}" readonly>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="hidden" class="form-control  inputForm " name="Detalles_Generales[idProcedimiento]" value="{{ $idProcedimiento }}" readonly>
                        </div>
                    </div>

                     <!--***************************************** DECLARACIÓN DE LOS RESULTADOS  *****************************************-->
                    <div style="margin-bottom: 2px;"></div>
                                        
                    @php
                        $declaracionResultadosPredeterminada = implode("\n", [
                            '*Este informe es único y exclusivamente válido en su estado original y completo; contando con las firmas de los responsables.',
                            '*Los procedimientos utilizados y resultados obtenidos en esta prueba se basan en el cumplimiento de la norma NMX-EC-17025-IMNC-2018.',
                            '*Los resultados emitidos se relacionan solamente con el ITEM con Id. interno _____ descrito en el presente informe, no aplica a items similares.',
                            '*Los datos emitidos en este iforme provienen del Reporte de Campo de Líquidos Penetrantes con Folio _______.',
                            '*Los datos referentes al ITEM son proporcionados por el cliente y plasmados en el reporte de campo y el informe de ensayo de la misma forma.',
                            '*Los resultados se expresan de acuerdo con la NOM-008-SCFI-NOM-008-SE-2021.',
                            '*La incertidumbre de medida se estima en base a la norma NMX-CH-140-IMNC-2002 "Guía para la evaluación de la incertidumbre de medida en los resultados de las mediciones".',
                            '*La incertidumbre de medida se expresa cuando el cliente lo solicite.',
                            '*La incertidumbre del laboratorio será informada con un factor de cobertura k= 2, es decir con un 95.45% de confianza.',
                            '*Las mediciones se realizan en unidades del SI, sin embargo se realiza la converción de milimetros a pulgadas, siendo el factor 1 in = 25.4 mm.',
                            '*Los resultados de las mediciones serán evaluados de acuerdo con los criterios de aceptación dentro del alcance del laboratorio.',
                            '*La regla de decisión será aplicada a los resultados de la medición para determinar si se ecuentra dentro o fuera de los criterios de aceptación.',
                            '*Cuando se menciona en el informe la fecha próxima de la siguiente prueba, es a solicitud del cliente.',
                            '*El laboratorio aplica la declaración binaria con zona de seguridad a los resultados obtenidos a menos que el cliente solicite y/o prescribe una regla de desición o criterios de aceptación y/o rechazo.',
                        ]);
                    @endphp
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded mb-2">Declaración de los resultados</div>
                        <label for="pt-declaracion-resultados" class="sr-only">Declaración de los resultados</label>
                        <textarea id="pt-declaracion-resultados" name="Datos_Equipo[DECLARACION_RESULTADOS]" class="form-control inputForm" rows="16">{{ old('Datos_Equipo.DECLARACION_RESULTADOS', $Datos_Equipo['DECLARACION_RESULTADOS'] ?? $declaracionResultadosPredeterminada) }}</textarea>
                        @error('Datos_Equipo.DECLARACION_RESULTADOS')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>


                    <!--***************************************** ADICIONES, DESVIACIONES O EXCLUIONES DEL MÉTODO  *****************************************-->
                    <div style="margin-bottom: 2px;"></div>
                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">ADICIONES, DESVIACIONES O EXCLUIONES DEL MÉTODO</div>
                    <p>
                    <div class="col-12 mb-3">
                        <label for="pt-adiciones-metodo" class="sr-only">Adiciones, desviaciones o exclusiones del método</label>
                        <textarea id="pt-adiciones-metodo" name="Datos_Equipo[ADICIONES_METODO]" class="form-control inputForm" rows="4" placeholder="Escriba las adiciones, desviaciones o exclusiones del método">{{ old('Datos_Equipo.ADICIONES_METODO', $Datos_Equipo['ADICIONES_METODO'] ?? '') }}</textarea>
                        @error('Datos_Equipo.ADICIONES_METODO')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <!-- Datos del/los ítems a inspeccionar -->
                    @php
                        $camposItemsPt = ['No', 'Item', 'Id_aico', 'MMN', 'Colada', 'Trazabilidad', 'Isometrico'];
                        $itemsPt = [];
                        foreach ($camposItemsPt as $campoItemPt) {
                            $valorItemPt = old('Datos_Equipo.' . $campoItemPt, $Datos_Equipo[$campoItemPt] ?? []);
                            $itemsPt[$campoItemPt] = is_array($valorItemPt) ? array_values($valorItemPt) : [$valorItemPt];
                        }
                        $cantidadItemsPt = max(1, max(array_map('count', $itemsPt)));
                    @endphp
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded mb-2">Datos del/los Item/s a Inspeccionar</div>
                        <div class="pt-items-contenedor">
                            <table class="table table-bordered table-striped table-sm" id="pt-items-tabla">
                                <caption class="sr-only">Ítems a inspeccionar. Agregue una fila por cada ítem.</caption>
                                <thead><tr>
                                    @foreach(['No.', 'ITEM / identificación', 'Id. AICO', 'Marca / Modelo / No. Serie', 'Colada', 'Trazabilidad del cliente', 'Isométrico / Plano', 'Eliminar'] as $tituloItemPt)
                                        <th scope="col" class="text-center align-middle">{{ $tituloItemPt }}</th>
                                    @endforeach
                                </tr></thead>
                                <tbody>
                                    @for($filaItemPt = 0; $filaItemPt < $cantidadItemsPt; $filaItemPt++)
                                        <tr>
                                            @foreach($camposItemsPt as $campoItemPt)
                                                <td><input type="text" class="form-control form-control-sm" name="Datos_Equipo[{{ $campoItemPt }}][]" aria-label="{{ $campoItemPt }}" value="{{ $itemsPt[$campoItemPt][$filaItemPt] ?? ($campoItemPt === 'No' ? $filaItemPt + 1 : '') }}"></td>
                                            @endforeach
                                            <td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm pt-item-eliminar" aria-label="Eliminar ítem">×</button></td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-end" style="gap:10px;">
                            <div>
                                <label for="pt-items-cantidad" class="mb-1">Cantidad de filas</label>
                                <select id="pt-items-cantidad" class="form-select" style="width:90px;">
                                    @for($cantidadFilaPt = 1; $cantidadFilaPt <= 20; $cantidadFilaPt++)
                                        <option value="{{ $cantidadFilaPt }}">{{ $cantidadFilaPt }}</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="button" id="pt-item-agregar" class="btn btn-success">Agregar filas</button>
                            <button type="button" id="pt-items-rellenar" class="btn btn-warning custom-btn">Rellenar Campos Vacios "---"</button>
                        </div>
                        @foreach($camposItemsPt as $campoItemPt)
                            @error('Datos_Equipo.' . $campoItemPt)<div class="text-danger">{{ $message }}</div>@enderror
                            @error('Datos_Equipo.' . $campoItemPt . '.*')<div class="text-danger">{{ $message }}</div>@enderror
                        @endforeach
                    </div>

                    <!--***************************************** CONSUMIBLES UTILIZADOS *****************************************-->
                            <div style="margin-bottom: 2px;"></div>
                                    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded mb-2">CONSUMIBLES UTILIZADOS</div>

                                    <div class="alert alert-info alert-dismissible mb-2">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-info"></i> Importante</h5>
                                        <p>Puedes Seleccionar un penetrante, removedor y un reveleador del menu o escribir directamente</p>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">PENETRANTES</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Penetrantes:</label>
                                            <select class="form-select inputForm" name="Consumible1" id="consumiblesSelect1">
                                            <option value="" selected disabled>Seleccione un Penetrante</option> <!-- Opción por defecto -->
                                                @foreach($idsGeneral_EyCs_Consumibles as $Consumibles)
                                                    <option value="{{ $Consumibles->idGeneral_EyC }}" @selected((string) old('Consumible1', $Datos_Equipo['ID_PENETRANTES'] ?? '') === (string) $Consumibles->idGeneral_EyC)
                                                            data-marca="{{ $Consumibles->Marca }}"
                                                            data-modelo="{{ $Consumibles->Modelo }}"
                                                            data-lote="{{ $Consumibles->almacen->Lote }}">
                                                        {{ $Consumibles->Nombre_E_P_BP }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" class="form-control  inputForm"
                                        id="IDInputC1"
                                        name="Datos_Equipo[ID_PENETRANTES]"
                                        placeholder=""
                                        value="{{old('Datos_Equipo.ID_PENETRANTES', $Datos_Equipo['ID_PENETRANTES'] ?? '')}}">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="marcaInputC1"
                                                name="Datos_Equipo[MARCA_PENETRANTES]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.MARCA_PENETRANTES', $Datos_Equipo['MARCA_PENETRANTES'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="modeloInputC1"
                                                name="Datos_Equipo[MODELO_PENETRANTES]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.MODELO_PENETRANTES', $Datos_Equipo['MODELO_PENETRANTES'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="loteInputC1"
                                                name="Datos_Equipo[LOTE_PENETRANTES]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.LOTE_PENETRANTES', $Datos_Equipo['LOTE_PENETRANTES'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIEMPO DE PENETRACIÓN:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[TIEMPO_PENETRANTES]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.TIEMPO_PENETRANTES', $Datos_Equipo['TIEMPO_PENETRANTES'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MÉTODO DE APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[METODO_PENETRANTES]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.METODO_PENETRANTES', $Datos_Equipo['METODO_PENETRANTES'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO/CLASE/FORMA:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[TIPO_PENETRANTES]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.TIPO_PENETRANTES', $Datos_Equipo['TIPO_PENETRANTES'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">REMOVEDOR</div>

                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Removedor:</label>
                                            <select class="form-select inputForm" name="Consumible2" id="consumiblesSelect2">
                                            <option value="" selected disabled>Seleccione un Removedor</option> <!-- Opción por defecto -->
                                                @foreach($idsGeneral_EyCs_Consumibles as $Consumibles)
                                                    <option value="{{ $Consumibles->idGeneral_EyC }}" @selected((string) old('Consumible2', $Datos_Equipo['ID_REMOVEDOR'] ?? '') === (string) $Consumibles->idGeneral_EyC)
                                                            data-marca="{{ $Consumibles->Marca }}"
                                                            data-modelo="{{ $Consumibles->Modelo }}"
                                                            data-lote="{{ $Consumibles->almacen->Lote }}">
                                                        {{ $Consumibles->Nombre_E_P_BP }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" class="form-control  inputForm"
                                        id="IDInputC2"
                                        name="Datos_Equipo[ID_REMOVEDOR]"
                                        placeholder=""
                                        value="{{old('Datos_Equipo.ID_REMOVEDOR', $Datos_Equipo['ID_REMOVEDOR'] ?? '')}}">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="marcaInputC2"
                                                name="Datos_Equipo[MARCA_REMOVEDOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.MARCA_REMOVEDOR', $Datos_Equipo['MARCA_REMOVEDOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="modeloInputC2"
                                                name="Datos_Equipo[MODELO_REMOVEDOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.MODELO_REMOVEDOR', $Datos_Equipo['MODELO_REMOVEDOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="loteInputC2"
                                                name="Datos_Equipo[LOTE_REMOVEDOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.LOTE_REMOVEDOR', $Datos_Equipo['LOTE_REMOVEDOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIEMPO DE SECADO:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[TIEMPO_REMOVEDOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.TIEMPO_REMOVEDOR', $Datos_Equipo['TIEMPO_REMOVEDOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MÉTODO DE APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[METODO_REMOVEDOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.METODO_REMOVEDOR', $Datos_Equipo['METODO_REMOVEDOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO/CLASE/FORMA:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[TIPO_REMOVEDOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.TIPO_REMOVEDOR', $Datos_Equipo['TIPO_REMOVEDOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-centerp-3 mb-2 bg-secondary text-white rounded">REVELADOR</div>

                                    <!-- Select para Reveleador -->
                                    <div class="col-sm-50 d-flex justify-content-center">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="inputSuccess">Reveleador:</label>
                                            <select class="form-select inputForm" name="Consumible3" id="consumiblesSelect3">
                                            <option value="" selected disabled>Seleccione un Reveleador</option> <!-- Opción por defecto -->
                                                @foreach($idsGeneral_EyCs_Consumibles as $Consumibles)
                                                    <option value="{{ $Consumibles->idGeneral_EyC }}" @selected((string) old('Consumible3', $Datos_Equipo['ID_REVELADOR'] ?? '') === (string) $Consumibles->idGeneral_EyC)
                                                            data-marca="{{ $Consumibles->Marca }}"
                                                            data-modelo="{{ $Consumibles->Modelo }}"
                                                            data-lote="{{ $Consumibles->almacen->Lote }}">
                                                        {{ $Consumibles->Nombre_E_P_BP }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" class="form-control  inputForm"
                                        id="IDInputC3"
                                        name="Datos_Equipo[ID_REVELADOR]"
                                        placeholder=""
                                        value="{{old('Datos_Equipo.ID_REVELADOR', $Datos_Equipo['ID_REVELADOR'] ?? '')}}">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="marcaInputC3"
                                                name="Datos_Equipo[MARCA_REVELADOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.MARCA_REVELADOR', $Datos_Equipo['MARCA_REVELADOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="modeloInputC3"
                                                name="Datos_Equipo[MODELO_REVELADOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.MODELO_REVELADOR', $Datos_Equipo['MODELO_REVELADOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE:</label>
                                            <input type="text" class="form-control  inputForm"
                                                id="loteInputC3"
                                                name="Datos_Equipo[LOTE_REVELADOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.LOTE_REVELADOR', $Datos_Equipo['LOTE_REVELADOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIEMPO DE REVELADO:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[TIEMPO_REVELADOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.TIEMPO_REVELADOR', $Datos_Equipo['TIEMPO_REVELADOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MÉTODO DE APLICACIÓN:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[METODO_REVELADOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.METODO_REVELADOR', $Datos_Equipo['METODO_REVELADOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO/CLASE/FORMA:</label>
                                            <input type="text" class="form-control  inputForm"
                                                name="Datos_Equipo[TIPO_REVELADOR]"
                                                placeholder=""
                                                value="{{old('Datos_Equipo.TIPO_REVELADOR', $Datos_Equipo['TIPO_REVELADOR'] ?? '')}}">
                                        </div>
                                    </div>

                                    <div class="alert alert-secondary d-none" role="alert"></div>

                                    <div class="col-sm-12"> 
                                        <hr style="border: none; height: 3px; background-color: rgb(6, 45, 97);">
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MATERIAL ABSORBENTE:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control  inputForm"
                                                    name="Datos_Equipo[Material_absorbente]"
                                                    placeholder="Ejemplo: Trapo. . ."
                                                    value="{{ old('Datos_Equipo.Material_absorbente', $Datos_Equipo['Material_absorbente'] ?? '') }}">
                                            </div>
                                        </div>
                                    </div>

                                            <!--***************************************** FIN DATOS DEL EQUIPO *****************************************-->
                                            <!--***************************************** INICIO RESULTADOS *****************************************-->

                                            <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">RESULTADOS</div>
                                                    
                                            <div style="margin-bottom: 5px;"></div>

                                            <div class="table-responsive">
                                                    <div class="alert alert-warning alert-dismissible">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                            <h5><i class="icon fas fa-info"></i> Importante</h5>
                                                        <p>La primera fila es para el llenado automatico de cada una de las columnas del formato.</p>
                                                    </div>
                                            <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas w-100">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="text-center align-middle">#</th>
                                                    <th rowspan="2" class="text-center align-middle">No.</th>
                                                    <th rowspan="2" class="text-center align-middle">ITEM</th>
                                                    <th rowspan="2" class="text-center align-middle">No. de Junta</th>
                                                    <th rowspan="2" class="text-center align-middle">No. Indicación</th>
                                                    <th rowspan="2" class="text-center align-middle">Tipo de Indicación</th>
                                                    <th colspan="3" class="text-center align-middle">DIM. DE INDICACIÓN</th>
                                                    <th colspan="2" class="text-center align-middle">LOCALIZACIÓN</th>
                                                    <th rowspan="2" class="text-center align-middle">Declaración de la Conformidad</th>
                                                    <th rowspan="2" class="text-center align-middle">Longitud Inspeccionada</th>
                                                    <th rowspan="2" class="text-center align-middle">Eliminar</th>
                                                </tr>

                                                <tr>
                                                    <th class="text-center align-middle">LARGO</th>
                                                    <th class="text-center align-middle">ANCHO</th>
                                                    <th class="text-center align-middle">Ø</th>
                                                    <th class="text-center align-middle">H.T.</th>
                                                    <th class="text-center align-middle">D.R</th>
                                                </tr>

                                                <tr id="inputRow">
                                                    <th></th> <!-- Para ID vacío -->
                                                    <th><input type="text" class="form-control default-input" data-column="1" style="width:100px"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="2" style="width:150px; "></th>
                                                    <th><input type="text" class="form-control default-input" data-column="3" style="width:120px"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="4" style="width: 140px"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="5" style="width: 80px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="6" style="width: 80px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="7" style="width: 80px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="8" style="width: 80px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="9" style="width: 120px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="10" style="width: 120px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="11" style="width: 120px;"></th>
                                                    <th><input type="text" class="form-control default-input" data-column="12" style="width: 120px;"></th>
                                                    <th></th> <!-- Para botón de eliminar -->
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                            </tbody>
                                    </table>
                                    </div>

                                    <input type="hidden" name="titulos_data" id="titulos_hidden">
                                    <input type="hidden" name="resultados_ordenados_pt" id="resultados_ordenados_pt">
                                    <input type="hidden" name="Tabla_CombinacionConfig"
                                        id="tablaCombinacionConfig"
                                        value="{{ old('Tabla_CombinacionConfig', $Datos_Equipo['TABLA_COMBINACION_CONFIG'] ?? '[]') }}">
                                    <!--<button id="addBtn" type="button" class="btn btn-success custom-btn">Agregar Fila</button>-->
                                    <div class="pt-resultados-toolbar d-flex flex-wrap justify-content-between align-items-center w-100 mb-3" style="gap: 0.5rem;">
                                        <div>
                                            <label for="numRows">Número de Filas:</label>
                                            <select id="numRows" class="form-select">
                                                @for ($i = 1; $i <= 500; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <button id="addBtn" type="button" class="btn btn-primary">Agregar Fila</button>

                                        <button id="addTituloBtn" type="button" class="btn btn-secondary">Agregar Título</button>

                                        <button id="addLongBtn" type="button" class="btn btn-success">Agregar Incertidumbre y Total</button>

                                        <button id="preFillBtn" type="button" class="btn btn-warning">Rellenar Campos Vacios "---"</button>

                                        

                                    </div>

                                    <div style="margin-bottom: 2px;"></div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Observaciones:</label>
                                                <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Observaciones]" placeholder="Ejemplo: LA INSPECCIÓN SE REALIZÓ DE LADO A Y B">{{old('Datos_Equipo.Observaciones', $Datos_Equipo['Observaciones'] ?? '')}}</textarea>
                                            </div>
                                        </div>

                                        <p>

                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DECLARACIÓN DE LA CONFORMIDAD</div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Criterio de Aceptación:</label>
                                                <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Criterio_Acpt]" placeholder="Ejemplo: ASTM . . . ">{{old('Datos_Equipo.Criterio_Acpt', $Datos_Equipo['Criterio_Acpt'] ?? '')}}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Valores de Referencia para la Evaluación de la Conformidad:</label>
                                                <textarea class="form-control  is-waning" id="inputSuccess" name="Datos_Equipo[Valores_referencia]" placeholder="Ejemplo: LAS INDICACIONES . . . ">{{old('Datos_Equipo.Valores_referencia', $Datos_Equipo['Valores_referencia'] ?? '')}}</textarea>
                                            </div>
                                        </div>

                                        <!-- Select para elegir el número de firmas -->
                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">Número de Firmas:</div>
                                        <div class="col-sm-15">
                                            <div class="form-group">
                                                <select class="form-select text-center" id="numFirmas" name="numFirmas">.
                                                    <option value="1" {{ old('numFirmas', $numFirmas) == 1 ? 'selected' : '' }}>1 Firma</option>
                                                    <option value="2" {{ old('numFirmas', $numFirmas) == 2 ? 'selected' : '' }}>2 Firmas</option>
                                                    <option value="3" {{ old('numFirmas', $numFirmas) == 3 ? 'selected' : '' }}>3 Firmas</option>
                                                    <option value="4" {{ old('numFirmas', $numFirmas) == 4 ? 'selected' : '' }}>4 Firmas</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- 1 DOS FIRMAS-->
                                        <div id="firmas1" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Firmas_Reportes1.Realizo', $Firmas['Realizo'] ?? '')}}"></th>
                                                    </tr>

                                                    <tr>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                    </tr>

                                                    <tr>

                                                        <td>
                                                            <div class="col-sm-50 d-flex justify-content-center">
                                                                    <div class="form-group text-center">
                                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                                        <select class="form-select inputForm" id="tecnicosSelect">
                                                                            <option value="" selected disabled>Seleccione un Técnico</option>

                                                                            @foreach($Tecnicos as $Tecnico)
                                                                                <option value="{{ $Tecnico->id }}"
                                                                                        data-name="{{ $Tecnico->name }}">
                                                                                    {{ $Tecnico->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes1[ID_TECNICO]"
                                                                            id="IDTECNICO"
                                                                            value="{{old('Firmas_Reportes1.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm"
                                                                            name="Firmas_Reportes1[NOMBRE_TECNICO]"
                                                                            id="NOMBRE_TECNICO"
                                                                            value="{{old('Firmas_Reportes1.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                            </div>
                                                            
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('Firmas_Reportes1.CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes1[EMPRESA_TECNICO]" placeholder="" value="{{ old('Firmas_Reportes1.EMPRESA_TECNICO', $Firmas['EMPRESA_TECNICO'] ?? 'Asesoría e Inspección en Construcción Costa Fuera, S.C.') }}" readonly></td>
                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 2 DOS FIRMAS-->
                                        <div id="firmas2" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Firmas_Reportes2.Realizo', $Firmas['Realizo'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[Vobo1]" placeholder="Ejemplo: Vobo1" value="{{old('Firmas_Reportes2.Vobo1', $Firmas['Vobo1'] ?? '')}}"></th>
                                                    
                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td>    
                                                            <div class="col-sm-50 d-flex justify-content-center">
                                                                    <div class="form-group text-center">
                                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                                        <select class="form-select inputForm" id="tecnicosSelect2">
                                                                            <option value="" selected disabled>Seleccione un Técnico</option>

                                                                            @foreach($Tecnicos as $Tecnico)
                                                                                <option value="{{ $Tecnico->id }}"
                                                                                        data-name="{{ $Tecnico->name }}">
                                                                                    {{ $Tecnico->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes2[ID_TECNICO]"
                                                                            id="IDTECNICO2"
                                                                            value="{{old('Firmas_Reportes2.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm"
                                                                            name="Firmas_Reportes2[NOMBRE_TECNICO]"
                                                                            id="NOMBRE_TECNICO2"
                                                                            value="{{old('Firmas_Reportes2.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <td>

                                                        </td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[NOMBRE_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('Firmas_Reportes2.NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('Firmas_Reportes2.CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[PUESTO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL ENCARGADO" value="{{old('Firmas_Reportes2.PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_TECNICO]" placeholder="" value="{{ old('Firmas_Reportes2.EMPRESA_TECNICO', $Firmas['EMPRESA_TECNICO'] ?? 'Asesoría e Inspección en Construcción Costa Fuera, S.C.') }}" readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes2[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('Firmas_Reportes2.EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 3 TRES FIRMAS-->
                                        <div id="firmas3" class="col-12">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Firmas_Reportes3.Realizo', $Firmas['Realizo'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo1]" placeholder="Ejemplo: Vobo1" value="{{old('Firmas_Reportes3.Vobo1', $Firmas['Vobo1'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[Vobo2]" placeholder="Ejemplo: Vobo2" value="{{old('Firmas_Reportes3.Vobo2', $Firmas['Vobo2'] ?? '')}}"></th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div class="col-sm-50 d-flex justify-content-center">
                                                                    <div class="form-group text-center">
                                                                    <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                                        <select class="form-select inputForm" id="tecnicosSelect3">
                                                                            <option value="" selected disabled>Seleccione un Técnico</option>

                                                                            @foreach($Tecnicos as $Tecnico)
                                                                                <option value="{{ $Tecnico->id }}"
                                                                                        data-name="{{ $Tecnico->name }}">
                                                                                    {{ $Tecnico->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                        <!-- hidden si quieres guardar el ID explícitamente -->
                                                                        <input type="hidden" name="Firmas_Reportes3[ID_TECNICO]"
                                                                            id="IDTECNICO3"
                                                                            value="{{old('Firmas_Reportes3.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                        <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                        <input type="text" class="form-control  inputForm"
                                                                            name="Firmas_Reportes3[NOMBRE_TECNICO]"
                                                                            id="NOMBRE_TECNICO3"
                                                                            value="{{old('Firmas_Reportes3.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        <td>

                                                        </td>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('Firmas_Reportes3.NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[NOMBRE_2DO_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('Firmas_Reportes3.NOMBRE_2DO_ENCARGADO', $Firmas['NOMBRE_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('Firmas_Reportes3.CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL ENCARGADO" value="{{old('Firmas_Reportes3.PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[PUESTO_2DO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL SEGUNDO ENCARGADO" value="{{old('Firmas_Reportes3.PUESTO_2DO_ENCARGADO', $Firmas['PUESTO_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_TECNICO]" placeholder="" value="{{ old('Firmas_Reportes3.EMPRESA_TECNICO', $Firmas['EMPRESA_TECNICO'] ?? 'Asesoría e Inspección en Construcción Costa Fuera, S.C.') }}" readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('Firmas_Reportes3.EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes3[EMPRESA_2DO_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('Firmas_Reportes3.EMPRESA_2DO_ENCARGADO', $Firmas['EMPRESA_2DO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <!-- 4 CUATRO FIRMAS-->
                                        <div id="firmas4" class="col-12" style="display: none;">
                                            <table class="table table-bordered table-striped dt-responsive tablas">
                                                <thead>
                                                    <tr>

                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Realizo]" placeholder="Ejemplo: Realizo" value="{{old('Firmas_Reportes4.Realizo', $Firmas['Realizo'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo1]" placeholder="Ejemplo: Vobo1" value="{{old('Firmas_Reportes4.Vobo1', $Firmas['Vobo1'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo2]" placeholder="Ejemplo: Vobo2" value="{{old('Firmas_Reportes4.Vobo2', $Firmas['Vobo2'] ?? '')}}"></th>
                                                        <td style="width: 30px;"></td>
                                                        <th><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[Vobo3]" placeholder="Ejemplo: Vobo3" value="{{old('Firmas_Reportes4.Vobo3', $Firmas['Vobo3'] ?? '')}}"></th>

                                                    </tr>

                                                    <tr>

                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                                        <td></td>
                                                        <td style="width: 200px; height:40px" class="lineaInferior"></td>

                                                    </tr>

                                                    <tr>

                                                        <td>
                                                            <div class="col-sm-50 d-flex justify-content-center">
                                                                <div class="form-group text-center">
                                                                <label class="col-form-label" for="inputSuccess">SELECCIÓN DE TÉCNICOS:</label>
                                                                    <select class="form-select inputForm" id="tecnicosSelect4">
                                                                        <option value="" selected disabled>Seleccione un Técnico</option>

                                                                        @foreach($Tecnicos as $Tecnico)
                                                                            <option value="{{ $Tecnico->id }}"
                                                                                    data-name="{{ $Tecnico->name }}">
                                                                                {{ $Tecnico->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                    <!-- hidden si quieres guardar el ID explícitamente -->
                                                                    <input type="hidden" name="Firmas_Reportes4[ID_TECNICO]"
                                                                        id="IDTECNICO4"
                                                                        value="{{old('Firmas_Reportes4.ID_TECNICO', $Firmas['ID_TECNICO'] ?? '')}}">
                                                                    <label class="col-form-label" for="inputSuccess">TECNICO SELECCIONADO:</label>
                                                                    <input type="text" class="form-control  inputForm"
                                                                        name="Firmas_Reportes4[NOMBRE_TECNICO]"
                                                                        id="NOMBRE_TECNICO4"
                                                                        value="{{old('Firmas_Reportes4.NOMBRE_TECNICO', $Firmas['NOMBRE_TECNICO'] ?? '')}}" readonly>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL ENCARGADO" value="{{old('Firmas_Reportes4.NOMBRE_ENCARGADO', $Firmas['NOMBRE_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_2DO_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL SEGUNDO ENCARGADO" value="{{old('Firmas_Reportes4.NOMBRE_2DO_ENCARGADO', $Firmas['NOMBRE_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[NOMBRE_3RO_ENCARGADO]" placeholder="Ejemplo: NOMBRE DEL TERCER ENCARGADO" value="{{old('Firmas_Reportes4.NOMBRE_3RO_ENCARGADO', $Firmas['NOMBRE_3RO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                                                        
                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[CARGO_TECNICO]" placeholder="Ejemplo: CARGO DEL TECNICO" value="{{old('Firmas_Reportes4.CARGO_TECNICO', $Firmas['CARGO_TECNICO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL ENCARGADO" value="{{old('Firmas_Reportes4.PUESTO_ENCARGADO', $Firmas['PUESTO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_2DO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL SEGUNDO ENCARGADO" value="{{old('Firmas_Reportes4.PUESTO_2DO_ENCARGADO', $Firmas['PUESTO_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[PUESTO_3RO_ENCARGADO]" placeholder="Ejemplo: PUESTO DEL TERCER ENCARGADO" value="{{old('Firmas_Reportes4.PUESTO_3RO_ENCARGADO', $Firmas['PUESTO_3RO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>

                                                    <tr>

                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_TECNICO]" placeholder="" value="{{ old('Firmas_Reportes4.EMPRESA_TECNICO', $Firmas['EMPRESA_TECNICO'] ?? 'Asesoría e Inspección en Construcción Costa Fuera, S.C.') }}" readonly></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL ENCARGADO" value="{{old('Firmas_Reportes4.EMPRESA_ENCARGADO', $Firmas['EMPRESA_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_2DO_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL SEGUNDO ENCARGADO" value="{{old('Firmas_Reportes4.EMPRESA_2DO_ENCARGADO', $Firmas['EMPRESA_2DO_ENCARGADO'] ?? '')}}"></td>
                                                        <td></td>
                                                        <td><input type="text" class="form-control  inputForm" name="Firmas_Reportes4[EMPRESA_3RO_ENCARGADO]" placeholder="Ejemplo: EMPRESA DEL TERCER ENCARGADO" value="{{old('Firmas_Reportes4.EMPRESA_3RO_ENCARGADO', $Firmas['EMPRESA_3RO_ENCARGADO'] ?? '')}}"></td>

                                                    </tr>
                                                    
                                                </thead>                            
                                            </table>
                                        </div>

                                        <p>

                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">FOTOS</div>

                                        <p>

                                        <!--IMAGENES CON COMENTARIOS-->
                                        <div class="form-group">
                                            <label for="imageCount">Número de imágenes a subir:</label>
                                            <select class="form-control" id="imageCount" name="imageCount" autocomplete="off">
                                                <option value="">Selecciona Cuantas Imagenes Quieres Agregar</option>
                                                @for ($i = 1; $i <= 50; $i++)
                                                    <option value="{{ $i }}">{{ $i }} Imagen{{ $i > 1 ? 'es' : '' }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        @if(!empty($Fotos_Comentarios))
                                            <div class="row">
                                                @foreach($Fotos_Comentarios as $index => $foto)
                                                    <div class="col-sm-6" id="image-container-{{ $index }}">
                                                        <div class="form-group">
                                                            <!-- Vista previa de la imagen existente -->
                                                            <label for="replace_image_{{ $index }}">Imagen Subida {{ $index + 1 }}:</label>
                                                            <div class="image-preview mt-2">
                                                                <img src="{{ asset($foto['ruta']) }}" class="img-fluid img-thumbnail" alt="Imagen Reporte">
                                                            </div>
                                                            <div class="form-check mt-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input imagen-hoja-checkbox"
                                                                    data-index="{{ $index }}"
                                                                    id="imagenHoja{{ $index }}"
                                                                    {{ !empty($foto['una_hoja']) && $foto['una_hoja'] == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="imagenHoja{{ $index }}">
                                                                    Imagen en una hoja
                                                                </label>
                                                            </div>
                                                            
                                                            <input type="hidden" name="imagen_hoja[{{ $index }}]" id="imagenHojaValue{{ $index }}" value="{{ $foto['una_hoja'] ?? 0 }}">
                                                            <!-- Campo para seleccionar una nueva imagen -->
                                                            <input type="file" class="form-control image-input mt-2"
                                                                id="replace_image_{{ $index }}"
                                                                name="replace_images[{{ $index }}]" accept="image/*">

                                                            <!-- Campo para el comentario -->
                                                            <textarea class="form-control mt-2" name="comments[{{ $index }}]" placeholder="Comentario">{{ $foto['comentario'] }}</textarea>

                                                            <!-- Campo oculto para la imagen en base64 -->
                                                            <input type="hidden" name="images_base64[{{ $index }}]" id="replace_image_{{ $index }}-base64">

                                                            <!-- Campo oculto para mantener la ruta de la imagen existente -->
                                                            <input type="hidden" name="existing_images[{{ $index }}]" value="{{ $foto['ruta'] }}">

                                                            <!-- Campo oculto para marcar imágenes eliminadas -->
                                                            <input type="hidden" name="deleted_images[]" id="deleted_image_{{ $index }}" value="">

                                                            <!-- Botón de eliminación -->
                                                            <button type="button" class="btn btn-danger mt-2 remove-image" data-index="{{ $index }}">Eliminar</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p>No hay imágenes disponibles.</p>
                                        @endif

                                        <div id="imageFieldsContainer" class="row">
                                            <!-- Aquí se agregarán dinámicamente los campos -->
                                        </div>

                                        <!-- Modal para recortar la imagen -->
                                        <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Recortar Imagen</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="img-container">
                                                            <img id="cropperImage" src="" style="max-width: 100%;">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelBtn">Cancelar</button>
                                                        <button type="button" id="rotateLeftBtn" class="btn btn-info">⟲ Rotar -90°</button>
                                                        <button type="button" id="rotateRightBtn" class="btn btn-info">⟳ Rotar +90°</button>
                                                        <button type="button" class="btn btn-primary" id="cropImageBtn">Recortar y Guardar</button>
                                                        <button type="button" class="btn btn-success" id="saveWithoutCropBtn">Guardar Sin Recortar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <p>

                                        <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">DATOS SOLDADOR</div>
                                        
                                        <p>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Num. de Soldador:</label>
                                                <input type="text" class="form-control  inputForm @error('Num_Soldador') is-invalid @enderror"
                                                    name="Detalles_Generales[Num_Soldador]" 
                                                    placeholder="Ejemplo: 12345"
                                                    value="{{ old('Detalles_Generales.Num_Soldador', $Detalles_Generales['Num_Soldador'] ?? '') }}">
                                                @error('Num_Soldador')
                                                        <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Nombre soldador/Iniciales:</label>
                                                <input type="text" class="form-control  inputForm @error('Nombre_Soldador') is-invalid @enderror"
                                                    name="Detalles_Generales[Nombre_Soldador]" 
                                                    placeholder="Ejemplo: Juan Pérez"
                                                    value="{{ old('Detalles_Generales.Nombre_Soldador', $Detalles_Generales['Nombre_Soldador'] ?? '') }}">
                                                @error('Nombre_Soldador')
                                                        <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>

                                        <p>

                                        <div class="d-flex justify-content-center align-items-center p-2 bg-success text-white rounded">SUBIR REPORTE FIRMADO</div>
                                        
                                        <p>

                                        <div class="row justify-content-center text-center">
                                            {{-- Columna para Subir/Sustituir Archivo --}}
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess"> 
                                                        @if ($Detalles_Generales['Reporte_Firmado'] ?? '') 
                                                            SUSTITUIR REPORTE FIRMADO 
                                                        @else 
                                                            SUBIR REPORTE FIRMADO 
                                                        @endif
                                                    </label>
                                                    <input type="file" class="form-control-file inputForm" name="Detalles_Generales[Reporte_Firmado]">
                                                    @if ($errors->any())
                                                        <div class="invalid-feedback d-block">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Columna para Ver Reporte (Solo aparece si existe el archivo) --}}
                                            @if ($Detalles_Generales['Reporte_Firmado'] ?? '')
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="inputSuccess">Ver Reporte Firmado</label>  
                                                        <div>                                           
                                                            <a href="{{ asset($Detalles_Generales['Reporte_Firmado']) }}" target="_blank" class="btn btn-primary long-button" role="button">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>                                                                                    
                                                        </div> 
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Conserva la selección guardada y permite revisar los PDF antes de actualizar. --}}
                                        @include('Reportes.IM.partials.documentos-usuario', ['formatoDocumentos' => 'F-SGLE-708-02-PT'])
                                        <div class="container">
                                            <div class="float-right">
                                                <button type="submit" class="btn btn-info bg-primary">Guardar</button>
                                            </div>

                                            <div class="float-left">
                                                <!--<button type="button" class="btn btn-info bg-success" id="guardarContinuarOC">Guardar y continuar</button>-->
                                            </div>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
@stop


{{-- Dependencias y comportamiento del formulario --}}
@section('js')
{{-- Navegacion al inicio/final con el mismo tamano y posicion de IM. --}}
<script src="{{ asset('js/Reportes_Fotos_Posicionables_02_B_04.js') }}?v={{ filemtime(public_path('js/Reportes_Fotos_Posicionables_02_B_04.js')) }}"></script>

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
<script src="{{ asset('js/Reportes_Edit.js') }}"></script>

<!-- Biblioteca para recorte de imagenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
{{-- Editor compartido de flechas y texto; se activa mediante data-anotaciones-fotos. --}}
<script src="{{ asset('js/Reportes_Anotaciones_Imagenes_06_B_01.js') }}?v={{ filemtime(public_path('js/Reportes_Anotaciones_Imagenes_06_B_01.js')) }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">


<script>
@php
    $camposResultadosPt = ['No', 'item', 'no_junta', 'no_indicacion', 'tipo_indicacion', 'largo', 'ancho', 'diametro', 'ht', 'dr', 'declaracion', 'long_inspeccionada'];
    $resultadosInicialesPt = [];
    if (session()->hasOldInput() && is_array(json_decode(old('resultados_ordenados_pt', ''), true))) {
        $resultadosInicialesPt = json_decode(old('resultados_ordenados_pt'), true);
    } elseif (session()->hasOldInput() && is_array(old('No'))) {
        $titulosPt = json_decode(old('titulos_data', '[]'), true) ?: [];
        $gruposPt = array_merge(['sin_titulo'], array_column($titulosPt, 'id'));
        foreach (array_unique($gruposPt) as $grupoPt) {
            foreach ($titulosPt as $tituloPt) {
                if ($tituloPt['id'] === $grupoPt) $resultadosInicialesPt[] = ['tipo' => 'titulo', 'grupo' => $grupoPt, 'texto' => $tituloPt['text'] ?? ''];
            }
            foreach ((array) old('No.' . $grupoPt, []) as $indicePt => $numeroPt) {
                $filaPt = [];
                foreach ($camposResultadosPt as $campoPt) $filaPt[$campoPt] = old($campoPt . '.' . $grupoPt . '.' . $indicePt, '');
                $resultadosInicialesPt[] = ['tipo' => 'fila', 'grupo' => $grupoPt, 'data' => $filaPt];
            }
            foreach ((array) old('Long_Inspecc.' . $grupoPt, []) as $longitudPt) $resultadosInicialesPt[] = ['tipo' => 'longitud', 'grupo' => $grupoPt, 'valor' => $longitudPt];
        }
    } else {
        foreach (($Grupo_Juntas_Re ?? []) as $bloquePt) {
            foreach ($bloquePt as $elementoPt) {
                if (!is_array($elementoPt)) continue;
                if (($elementoPt['tipo'] ?? '') === 'fila') {
                    $elementoPt['data']['no_junta'] ??= $elementoPt['data']['componente'] ?? '';
                    $elementoPt['data']['declaracion'] ??= $elementoPt['data']['evaluacion'] ?? '';
                }
                $resultadosInicialesPt[] = $elementoPt;
            }
        }
    }
@endphp
/* Resultados PT: el orden coincide con los encabezados del formulario. */
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('dynamicTable');
    const body = table.tBodies[0];
    const form = table.closest('form');
    const fields = @json($camposResultadosPt);
    const labels = ['No.', 'ITEM', 'No. de Junta', 'No. Indicación', 'Tipo de Indicación', 'Largo', 'Ancho', 'Ø', 'H.T.', 'D.R.', 'Declaración de la Conformidad', 'Longitud Inspeccionada'];
    const initial = @json($resultadosInicialesPt);
    const storageKey = 'pt17025-resultados-' + @json($id ?? 'nuevo');
    table.querySelectorAll('.default-input').forEach(input => {
        const index = Number(input.dataset.column) - 1;
        input.value = '';
        input.placeholder = labels[index];
        input.setAttribute('aria-label', labels[index]);
        input.addEventListener('input', () => {
            const field = fields[index];
            if (!field) return;
            Array.from(body.rows).filter(row => !row.matches('.titulo-row, .long-row')).forEach(row => {
                const target = row.querySelector(`input[name="${field}[${row.dataset.titulo}][]"]`);
                if (target) target.value = input.value;
            });
            serialize();
        });
    });
    let mergeManager = null;
    let currentGroup = 'sin_titulo';
    let titleCount = 0;
    body.replaceChildren();
    function inputCell(name, value, label) {
        const td = document.createElement('td');
        const input = document.createElement('input');
        input.type = 'text'; input.className = 'form-control'; input.name = name;
        input.value = value ?? ''; input.placeholder = label; input.setAttribute('aria-label', label);
        td.append(input); return td;
    }
    function removeCell(title = false) {
        const td = document.createElement('td'); const button = document.createElement('button');
        button.type = 'button'; button.className = 'btn btn-danger ' + (title ? 'pt-borrar-titulo' : 'pt-borrar-resultado');
        button.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';
        button.setAttribute('aria-label', title ? 'Eliminar título y sus filas' : 'Eliminar fila'); td.append(button); return td;
    }
    function addTitle(group, text = '') {
        const row = document.createElement('tr'); row.className = 'titulo-row'; row.dataset.titulo = group;
        const td = inputCell(`titulos_text[${group}]`, text, 'Ingrese título'); td.colSpan = 13;
        td.firstElementChild.classList.add('titulo-text');
        const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.className = 'titulo-id'; hidden.name = 'titulos_ids[]'; hidden.value = group; td.append(hidden);
        row.append(td, removeCell(true)); body.append(row); currentGroup = group;
    }
    function addRow(group, values = {}) {
        const row = document.createElement('tr'); row.dataset.titulo = group;
        const number = document.createElement('td'); number.className = 'row-number'; row.append(number);
        fields.forEach((field, index) => row.append(inputCell(`${field}[${group}][]`, values[field] ?? '', labels[index])));
        row.append(removeCell()); body.append(row);
    }
    function addLong(group, text = '') {
        const row = document.createElement('tr'); row.className = 'long-row'; row.dataset.titulo = group;
        const label = document.createElement('td'); label.colSpan = 11; label.textContent = 'Longitud total inspeccionada:';
        const td = inputCell(`Long_Inspecc[${group}][]`, text, 'Longitud total inspeccionada'); td.colSpan = 2; td.firstElementChild.classList.add('long-text');
        row.append(label, td, removeCell()); body.append(row);
    }
    function addSummary(group, incertidumbre = '', total = '') {
        const row = document.createElement('tr'); row.className = 'long-row resumen-row'; row.dataset.titulo = group;
        const empty = document.createElement('td'); empty.colSpan = 6;
        const uLabel = document.createElement('th'); uLabel.colSpan = 3; uLabel.className = 'text-center align-middle'; uLabel.textContent = 'Incertidumbre ( U )';
        const u = inputCell('', incertidumbre, 'Incertidumbre'); u.colSpan = 2; u.firstElementChild.classList.add('resumen-incertidumbre');
        const tLabel = document.createElement('th'); tLabel.className = 'text-center align-middle'; tLabel.textContent = 'Total';
        const t = inputCell('', total, 'Total'); t.firstElementChild.classList.add('resumen-total');
        row.append(empty, uLabel, u, tLabel, t, removeCell()); body.append(row);
    }
    function serialize() {
        const data = Array.from(body.rows).map(row => {
            const group = row.dataset.titulo || 'sin_titulo';
            if (row.classList.contains('titulo-row')) return {tipo:'titulo', grupo:group, texto:row.querySelector('.titulo-text').value};
            if (row.classList.contains('resumen-row')) return {tipo:'resumen', grupo:group, incertidumbre:row.querySelector('.resumen-incertidumbre').value, total:row.querySelector('.resumen-total').value};
            if (row.classList.contains('long-row')) return {tipo:'longitud', grupo:group, valor:row.querySelector('.long-text').value};
            return {tipo:'fila', grupo:group, data:Object.fromEntries(fields.map(field => [field, row.querySelector(`input[name="${field}[${group}][]"]`).value]))};
        });
        document.getElementById('titulos_hidden').value = JSON.stringify(data.filter(item => item.tipo === 'titulo').map(item => ({id:item.grupo,text:item.texto})));
        document.getElementById('resultados_ordenados_pt').value = JSON.stringify(data);
        try { sessionStorage.setItem(storageKey, JSON.stringify(data)); } catch (_) {}
    }
    function reindex() {
        let index = 0;
        Array.from(body.rows).filter(row => !row.matches('.titulo-row, .long-row')).forEach(row => { row.cells[0].textContent = ++index; });
        currentGroup = Array.from(body.querySelectorAll('.titulo-row')).at(-1)?.dataset.titulo || 'sin_titulo';
        if (mergeManager) mergeManager.refresh();
        serialize();
    }
    let restored = initial;
    if (!Array.isArray(restored)) restored = [];
    restored.forEach(item => {
        const group = item.grupo || 'sin_titulo';
        if (item.tipo === 'titulo') addTitle(group, item.texto);
        else if (item.tipo === 'longitud') addLong(group, item.valor);
        else if (item.tipo === 'resumen') addSummary(group, item.incertidumbre, item.total);
        else if (item.tipo === 'fila') addRow(group, item.data || {});
    });
    if (window.ReportesCombinacionCeldasAgrupadas && window.jQuery) {
        mergeManager = window.ReportesCombinacionCeldasAgrupadas.crearAdministrador({
            tbodySelector:'#dynamicTable tbody', hiddenSelector:'#tablaCombinacionConfig',
            selectionInfoSelector:'#tablaMergeSelectionInfo', inferirColumnasCombinables:true
        });
        mergeManager.init();
    }
    document.getElementById('addBtn').addEventListener('click', () => {
        const count = Math.min(500, Math.max(1, Number(document.getElementById('numRows').value) || 1));
        for (let index = 0; index < count; index++) {
            const values = Object.fromEntries(fields.map((field, column) => [field, table.querySelector(`.default-input[data-column="${column + 1}"]`)?.value || '']));
            addRow(currentGroup, values);
            const last = body.lastElementChild;
            let countBlock = 0;
            for (let row = last; row && !row.matches('.long-row, .titulo-row'); row = row.previousElementSibling) countBlock++;
            if (countBlock === 15) addSummary(currentGroup);
        }
        reindex();
    });
    document.getElementById('addTituloBtn').addEventListener('click', () => { addTitle(`titulo_pt_${Date.now()}_${++titleCount}`); reindex(); });
    document.getElementById('addLongBtn').addEventListener('click', () => { addSummary(currentGroup); reindex(); });
    body.addEventListener('click', event => {
        const button = event.target.closest('.pt-borrar-titulo, .pt-borrar-resultado'); if (!button) return;
        const row = button.closest('tr');
        if (button.classList.contains('pt-borrar-titulo')) {
            if (mergeManager) mergeManager.clearGroup(row.dataset.titulo);
            Array.from(body.rows).filter(item => item.dataset.titulo === row.dataset.titulo).forEach(item => item.remove());
        } else {
            if (mergeManager && !row.classList.contains('long-row')) mergeManager.handleDeleteRow(window.jQuery(row));
            row.remove();
        }
        reindex();
    });
    body.addEventListener('input', serialize);
    form.addEventListener('pt:serializar-resultados', serialize);
    form.addEventListener('submit', serialize);
    // The shared script fills empty cells; save the resulting values afterwards.
    document.getElementById('preFillBtn').addEventListener('click', () => setTimeout(serialize, 0));
    reindex();
});

    /*Selects */
        $(document).ready(function() {
            function actualizarInputsC1() {
                var selectedOption = $('#consumiblesSelect1').find('option:selected');
                
                // En lugar de .data(), usamos .val() porque el ID es el value del option
                var ID = selectedOption.val() || '';
                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var lote = selectedOption.data('lote') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#IDInputC1').val(ID);
                $('#marcaInputC1').val(marca);
                $('#modeloInputC1').val(modelo);
                $('#loteInputC1').val(lote);
            }
                // Evento cuando se cambia la selección en el select
                $('#consumiblesSelect1').on('change', function() {
                    actualizarInputsC1();
                });

            const selectedOptionLocalCons1 = localStorage.getItem(document.getElementById('F-SGLE-708-02-PT').id+'_Consumible1');
            selectedOptionLocalCons1 != null ?  ($('#consumiblesSelect1').val(selectedOptionLocalCons1),actualizarInputsC1()):"";

            // Cuando se cambia la selección
            $('#consumiblesSelect1').on('change', function() {
                actualizarInputsC1();
            });
            
            function actualizarInputsC2() {
                var selectedOption = $('#consumiblesSelect2').find('option:selected');

                // En lugar de .data(), usamos .val() porque el ID es el value del option
                var ID = selectedOption.val() || '';
                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var lote = selectedOption.data('lote') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#IDInputC2').val(ID);
                $('#marcaInputC2').val(marca);
                $('#modeloInputC2').val(modelo);
                $('#loteInputC2').val(lote);
            }

            const selectedOptionLocalCons2 = localStorage.getItem(document.getElementById('F-SGLE-708-02-PT').id+'_Consumible2');
            selectedOptionLocalCons2 != null ?  ($('#consumiblesSelect2').val(selectedOptionLocalCons2),actualizarInputsC2()):"";

            // Evento cuando se cambia la selección en el select
            $('#consumiblesSelect2').on('change', function() {
                actualizarInputsC2();
            });
            
            function actualizarInputsC3() {
                var selectedOption = $('#consumiblesSelect3').find('option:selected');

                // En lugar de .data(), usamos .val() porque el ID es el value del option
                var ID = selectedOption.val() || '';
                // Extraer los datos de los atributos "data-"
                var marca = selectedOption.data('marca') || '';
                var modelo = selectedOption.data('modelo') || '';
                var lote = selectedOption.data('lote') || '';

                // Rellenar los inputs con los valores obtenidos
                $('#IDInputC3').val(ID);
                $('#marcaInputC3').val(marca);
                $('#modeloInputC3').val(modelo);
                $('#loteInputC3').val(lote);
            }

            const selectedOptionLocalCons3 = localStorage.getItem(document.getElementById('F-SGLE-708-02-PT').id+'_Consumible3');
            selectedOptionLocalCons3 != null ?  ($('#consumiblesSelect3').val(selectedOptionLocalCons3),actualizarInputsC3()):"";

            // Evento cuando se cambia la selección en el select
            $('#consumiblesSelect3').on('change', function() {
                actualizarInputsC3();
            });

        });

    /*F-SGLE-708-02-PT*/
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('F-SGLE-708-02-PT');
        if (!form) return;

        // Guardar en localStorage al escribir
        //form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
          //  el.addEventListener('input', function () {
            //    localStorage.setItem('F-SGLE-708-02-PT_' + el.name, el.value);
            //});
        //});

        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.name || el.closest('#dynamicTable')) return;
            el.addEventListener('input', function () {
                if (el.closest('#dynamicTable')) return; // Ignora inputs de la tabla
                localStorage.setItem('F-SGLE-708-02-PT_' + el.name, el.value);
            });
        });

        // Restaurar al cargar la página (solo si el campo está vacío)
        form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.name || el.closest('#dynamicTable')) return;
            if (!el.value) {
                const value = localStorage.getItem('F-SGLE-708-02-PT_' + el.name);
                if (value !== null) el.value = value;
            }
        });

        // Limpiar localStorage al enviar el formulario
        form.addEventListener('submit', function () {
            form.querySelectorAll('input:not([type="file"]), textarea, select').forEach(function (el) {
            if (!el.name || el.closest('#dynamicTable')) return;
                localStorage.removeItem('F-SGLE-708-02-PT_' + el.name);
                //localStorage.clear();
            });
        });
    });
</script>
<script src="{{ asset('js/Reportes_CombinacionCeldasAgrupadas.js') }}"></script>
<!-- Equipos de medición utilizados -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('pt-equipos-filas');
    const select = document.getElementById('pt-equipo-catalogo');
    const agregarEquipos = document.getElementById('pt-equipos-agregar');
    const listaEquipos = document.createElement('div');
    listaEquipos.className = 'pt-equipo-lista';
    select.after(listaEquipos);
    select.hidden = true;
    const opcionesEquipo = Array.from(select.options).filter(option => option.value);
    function actualizarListaEquipos() {
        listaEquipos.replaceChildren();
        opcionesEquipo.forEach(option => {
            const label = document.createElement('label');
            label.className = 'pt-equipo-opcion' + (option.selected ? ' pt-selected' : '');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox'; checkbox.checked = option.selected;
            const texto = document.createElement('span');
            const partes = option.textContent.trim().split(' — ');
            const nombre = document.createElement('strong'); nombre.textContent = partes[0];
            const detalle = document.createElement('small'); detalle.textContent = partes.slice(1).join(' · ');
            texto.append(nombre, detalle); label.append(checkbox, texto);
            checkbox.addEventListener('change', () => {
                option.selected = checkbox.checked;
                label.classList.toggle('pt-selected', checkbox.checked);
                actualizarContadorEquipos();
            });
            listaEquipos.append(label);
        });
        if (!listaEquipos.children.length) {
            const vacio = document.createElement('div'); vacio.className = 'pt-equipo-empty';
            vacio.textContent = opcionesEquipo.length ? 'No se encontraron equipos con esa búsqueda.' : 'No hay equipos disponibles.';
            listaEquipos.append(vacio);
        }
        actualizarContadorEquipos();
    }
    function actualizarContadorEquipos() {
        const cantidad = select.selectedOptions.length;
        agregarEquipos.textContent = cantidad ? `Agregar ${cantidad} equipo${cantidad === 1 ? '' : 's'} seleccionado${cantidad === 1 ? '' : 's'}` : 'Selecciona equipos para agregar';
        agregarEquipos.disabled = cantidad === 0;
    }
    actualizarListaEquipos();
    const catalogo = @json($catalogoPt);
    let filas = @json(array_values($filasPt));
    function render() {
        tbody.replaceChildren();
        filas.forEach((equipo, index) => {
            const tr = document.createElement('tr');
            const id = document.createElement('input');
            id.type = 'hidden'; id.name = `Datos_Equipo[EQUIPOS_MEDICION][${index}][ID]`; id.value = equipo.ID || '';
            ['DESCRIPCION','MARCA','MODELO','NO_SERIE','TRAZABILIDA','REFERENCIA','NO_CERTIFICADO'].forEach((campo, column) => {
                const td = document.createElement('td'); td.className = 'align-middle text-center';
                const input = document.createElement('input');
                input.className = 'form-control form-control-sm'; input.style.minWidth = '120px';
                input.name = `Datos_Equipo[EQUIPOS_MEDICION][${index}][${campo}]`;
                input.value = equipo[campo] || '';
                input.setAttribute('aria-label', campo);
                input.addEventListener('input', () => equipo[campo] = input.value);
                if (column === 0) td.append(id);
                input.id = `pt-equipo-${index}-${campo}`;
                td.append(input); tr.append(td);
            });
            const td = document.createElement('td'); td.className = 'align-middle text-center'; const button = document.createElement('button');
            button.type = 'button'; button.className = 'btn btn-danger btn-sm'; button.textContent = '×'; button.setAttribute('aria-label', 'Eliminar equipo');
            button.addEventListener('click', () => { filas.splice(index, 1); render(); });
            td.append(button); tr.append(td); tbody.append(tr);
        });
    }
    document.getElementById('pt-equipos-agregar').addEventListener('click', () => {
        Array.from(select.selectedOptions).forEach(option => {
            const equipo = catalogo.find(item => String(item.ID) === option.value);
            if (equipo && !filas.some(item => String(item.ID) === String(equipo.ID))) filas.push({...equipo, TRAZABILIDA:'', REFERENCIA:''});
            option.selected = false;
        });
        actualizarListaEquipos();
        render();
    });
    render();
});
</script>

<!-- Filas de ítems a inspeccionar -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabla = document.getElementById('pt-items-tabla');
    const body = tabla.tBodies[0];
    const modelo = body.rows[0].cloneNode(true);
    document.getElementById('pt-items-rellenar').addEventListener('click', function () {
        body.querySelectorAll('input[type="text"]:not([name="Datos_Equipo[No][]"])').forEach(input => {
            if (input.value.trim()) return;
            input.value = '---';
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
    function numerarItems() {
        Array.from(body.rows).forEach((row, index) => {

            row.querySelectorAll('input').forEach(input => input.setAttribute('aria-label', `${input.name.split('[')[1].replace(']', '')}, fila ${index + 1}`));
        });
    }
    document.getElementById('pt-item-agregar').addEventListener('click', function () {
        const cantidad = Number(document.getElementById('pt-items-cantidad').value);
        if (!Number.isInteger(cantidad) || cantidad < 1 || cantidad > 20) return;
        const numerosItems = Array.from(body.querySelectorAll('input[name="Datos_Equipo[No][]"]')).map(input => Number(input.value)).filter(Number.isFinite);
        let siguienteNumero = Math.max(0, ...numerosItems) + 1;
        let primeraFila;
        for (let index = 0; index < cantidad; index++) {
            const row = modelo.cloneNode(true);
            row.querySelectorAll('input').forEach(input => input.value = '');
            row.querySelector('input[name="Datos_Equipo[No][]"]').value = siguienteNumero++;
            body.append(row);
            if (!primeraFila) primeraFila = row;
        }
        numerarItems();
        primeraFila.querySelector('input[name="Datos_Equipo[Item][]"]').focus();
    });
    body.addEventListener('click', function (event) {
        const button = event.target.closest('.pt-item-eliminar');
        if (!button) return;
        if (body.rows.length > 1) button.closest('tr').remove();
        else body.rows[0].querySelectorAll('input').forEach(input => input.value = '');
        numerarItems();
    });
    numerarItems();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('F-SGLE-708-02-PT');
    const clienteSelect = document.getElementById('campoClienteSelect');
    const clienteInput = document.getElementById('campoClienteInput');
    function mostrarCliente() {
        const existente = form.querySelector('input[name="TieneCliente"]:checked')?.value === 'si';
        clienteSelect.style.display = existente ? 'block' : 'none';
        clienteInput.style.display = existente ? 'none' : 'block';
    }
    form.querySelectorAll('input[name="TieneCliente"]').forEach(radio => radio.addEventListener('change', mostrarCliente));
    mostrarCliente();
    const contrato = document.getElementById('campoContrato');
    form.querySelectorAll('input[name="TieneContrato"]').forEach(radio => radio.addEventListener('change', async function () {
        contrato.readOnly = this.value === 'no';
        contrato.required = this.value === 'si';
        if (this.value !== 'no' || contrato.value.trim()) return;
        try {
            const response = await fetch(@json(url('/api/siguiente-contrato-interno')));
            if (!response.ok) throw new Error('No se pudo generar el contrato interno');
            const data = await response.json(); contrato.value = data.siguiente;
        } catch (error) { contrato.readOnly = false; console.error(error); }
    }));
});
</script>
<script>
/* Envío de PT: valida el formulario activo y evita bloqueos del manejador genérico. */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('F-SGLE-708-02-PT');
    if (!form) return;
    form.noValidate = true;
    function mostrarErrorGuardado(message, field) {
        let notice = document.getElementById('pt-error-guardado');
        if (!notice) { notice = document.createElement('div'); notice.id = 'pt-error-guardado'; notice.className = 'alert alert-danger'; notice.setAttribute('role', 'alert'); form.prepend(notice); }
        notice.textContent = message;
        if (field) {
            field.classList.add('is-invalid');
            field.scrollIntoView({behavior:'smooth', block:'center'});
            field.focus({preventScroll:true});
            field.addEventListener('input', () => field.classList.remove('is-invalid'), {once:true});
        } else notice.scrollIntoView({behavior:'smooth',block:'center'});
        if (window.Swal) Swal.fire({icon:'warning',title:'Revisa los datos del reporte',text:message});
    }
    // La edicion debe usar los valores del reporte, no otro borrador local.
    const numeroFirmasSelectPt = document.getElementById('numFirmas');
    const numeroFirmasOriginalPt = Array.from(numeroFirmasSelectPt.options).find(option => option.defaultSelected);
    if (numeroFirmasOriginalPt) numeroFirmasSelectPt.value = numeroFirmasOriginalPt.value;
    [1,2,3,4].forEach(number => {
        const suffix = number === 1 ? '' : number;
        const select = document.getElementById('tecnicosSelect' + suffix);
        const id = document.getElementById('IDTECNICO' + suffix);
        const nombre = document.getElementById('NOMBRE_TECNICO' + suffix);
        if (!select || !id || !nombre) return;
        id.value = id.defaultValue;
        nombre.value = nombre.defaultValue;
        select.value = id.value;
        select.addEventListener('change', () => {
            id.value = select.value;
            nombre.value = select.selectedOptions[0]?.dataset.name || '';
        });
    });
    function sincronizarFirmasPt() {
        const count = String(document.getElementById('numFirmas').value || '1');
        [1,2,3,4].forEach(number => {
            const block = document.getElementById('firmas' + number);
            const select = document.getElementById('tecnicosSelect' + (number === 1 ? '' : number));
            if (block) block.style.display = String(number) === count ? 'block' : 'none';
            if (select) {
                select.required = String(number) === count && !form.querySelector(`input[name="Firmas_Reportes${number}[ID_TECNICO]"]`)?.value;
                const nombre = form.querySelector(`input[name="Firmas_Reportes${number}[NOMBRE_TECNICO]"]`);
                // Preservar el nombre hist?rico hasta cambiar la selecci?n.
            }
        });
    }
    [1,2,3,4].forEach(number => {
        const select = document.getElementById('tecnicosSelect' + (number === 1 ? '' : number));
        if (select) select.addEventListener('change', sincronizarFirmasPt);
    });
    document.getElementById('numFirmas').addEventListener('change', sincronizarFirmasPt);
    sincronizarFirmasPt();
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        sincronizarFirmasPt();
        const existingClient = form.querySelector('input[name="TieneCliente"]:checked')?.value !== 'no';
        const client = document.getElementById(existingClient ? 'campoClienteSelect' : 'campoClienteInput');
        if (!client.value.trim()) { mostrarErrorGuardado('Selecciona o escribe el cliente del reporte.', client); return; }
        const invalid = Array.from(form.elements).find(field => field.willValidate && !field.checkValidity());
        if (invalid) {
            const label = invalid.id.startsWith('tecnicosSelect') ? 'Selecciona el técnico en la sección de firmas.' : invalid.id === 'campoContrato' ? 'Completa el contrato del reporte.' : invalid.validationMessage;
            mostrarErrorGuardado(label, invalid); return;
        }
        const body = document.querySelector('#dynamicTable tbody');
        if (!body.querySelector('tr:not(.titulo-row):not(.long-row)')) { mostrarErrorGuardado('Agrega al menos una fila de resultados antes de guardar.'); return; }
        form.dispatchEvent(new Event('pt:serializar-resultados'));
        const titles = Array.from(body.querySelectorAll('.titulo-row')).map(row => ({id:row.dataset.titulo,text:row.querySelector('.titulo-text')?.value || ''}));
        document.getElementById('titulos_hidden').value = JSON.stringify(titles);
        const button = form.querySelector('button[type="submit"]');
        if (button) { button.disabled = true; button.textContent = 'Guardando…'; }
        HTMLFormElement.prototype.submit.call(form);
    }, true);
});
</script>

<script>
/* Tarjetas de fotografías PT: diseño de PINS 23, sin títulos ni checks. */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('F-SGLE-708-02-PT');
    if (!form) return;
    const observer = new MutationObserver(organizarFotosPt);
    function organizarFotosPt() {
        observer.disconnect();
        form.querySelectorAll('input.image-input[type="file"]').forEach(input => {
            const group = input.closest('.form-group');
            if (!group) return;
            if (!group.classList.contains('foto-pt-tarjeta')) {
                group.classList.add('foto-pt-tarjeta');
                const original = Array.from(group.querySelectorAll('label')).find(label => label.htmlFor === input.id);
                const header = document.createElement('div'); header.className = 'foto-pt-cabecera';
                header.textContent = original?.textContent.trim() || 'Fotografía';
                if (original) original.hidden = true;
                group.prepend(header);
                group.querySelectorAll('.form-check').forEach(check => check.remove());
                const preview = group.querySelector('.image-preview');
                if (preview) {
                    if (!preview.id) preview.id = input.id + '-preview';
                    header.after(preview);
                }
                const comment = group.querySelector('textarea[name^="comments"]');
                if (comment) (preview || header).after(comment);
                const actions = document.createElement('div'); actions.className = 'foto-pt-acciones';
                const replacement = document.createElement('label'); replacement.className = 'foto-pt-reemplazo';
                replacement.append(document.createTextNode('Subir o reemplazar imagen'), input); actions.append(replacement);
                group.querySelectorAll('.remove-image, .delete-image').forEach(button => actions.append(button));
                group.querySelectorAll('br').forEach(br => br.remove());
                group.append(actions);
            }
            const preview = group.querySelector('.image-preview');
            const image = preview?.querySelector('img');
            const hasImage = !!(image && image.getAttribute('src'));
            if (preview) preview.hidden = !hasImage;
            const annotate = group.querySelector('[data-anotar-imagen-guardada]');
            if (annotate) {
                if (annotate.textContent !== 'Señalar hallazgos') annotate.textContent = 'Señalar hallazgos';
                annotate.hidden = !hasImage;
                const actions = group.querySelector('.foto-pt-acciones');
                if (annotate.parentElement !== actions) actions.prepend(annotate);
            }
        });
        const hasCards = !!form.querySelector('.foto-pt-tarjeta');
        form.querySelectorAll('p').forEach(p => { if (p.textContent.trim() === 'No hay imágenes disponibles.') p.hidden = hasCards; });
        observer.observe(form, {childList:true, subtree:true, attributes:true, attributeFilter:['src']});
    }
    organizarFotosPt();
});
</script>

<script>
/* Navegación y vista ampliada de las fotografías. */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('F-SGLE-708-02-PT');
    const navigation = document.createElement('nav'); navigation.className = 'pt-navegacion-form'; navigation.setAttribute('aria-label', 'Navegación del formulario');
    [['↑','Ir al inicio',false],['↓','Ir al final',true]].forEach(([symbol,label,bottom]) => {
        const button = document.createElement('button'); button.type = 'button'; button.textContent = symbol; button.title = label; button.setAttribute('aria-label', label);
        button.addEventListener('click', () => {
            const box = form.getBoundingClientRect();
            const top = window.scrollY + (bottom ? box.bottom - window.innerHeight + 30 : box.top - 90);
            window.scrollTo({top:Math.max(0,top),behavior:window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'});
        }); navigation.append(button);
    });
    document.body.append(navigation);
    const dialog = document.createElement('dialog'); dialog.className = 'pt-foto-visor'; dialog.setAttribute('aria-label', 'Vista ampliada de la imagen');
    const header = document.createElement('div'); header.className = 'pt-visor-cabecera';
    const title = document.createElement('strong'); title.textContent = 'Vista previa de la imagen';
    const close = document.createElement('button'); close.type = 'button'; close.className = 'btn btn-outline-secondary'; close.textContent = 'Cerrar ×';
    close.addEventListener('click', () => dialog.close()); header.append(title,close);
    const image = document.createElement('img'); image.alt = 'Imagen del reporte ampliada'; dialog.append(header,image); document.body.append(dialog);
    dialog.addEventListener('click', event => { if (event.target === dialog) { const box = dialog.getBoundingClientRect(); if (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom) dialog.close(); } });
    form.addEventListener('click', event => {
        const trigger = event.target.closest('.foto-pt-tarjeta .image-preview img');
        if (!trigger) return;
        const photo = trigger.closest('.foto-pt-tarjeta')?.querySelector('.image-preview img');
        if (!photo?.getAttribute('src')) return;
        image.src = photo.currentSrc || photo.src;
        if (!dialog.open) dialog.showModal();
    });
});
</script>
@endsection
