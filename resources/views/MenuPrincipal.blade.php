
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')  
<!-- form start -->
<br>
<br>
<br>
@php 
//dd($user);
//dd($certificados);
//dd($notificaciones);
@endphp
<br>
<br>
<br>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Clientes</h3>
                            <a href="javascript:void(0);">Ver Reportes</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">820</span>
                                <span>Servicios realizados</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> 12.5%
                            </span>
                            <span class="text-muted">Since last week</span>
                            </p>
                        </div>
                        <div class="position-relative mb-4">
                        <canvas id="visitors-chart" height="200"></canvas>
                        </div>
                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> This Week
                            </span>
                            <span>
                                <i class="fas fa-square text-gray"></i> Last Week
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Consumibles</h3>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>Productos</th>
                                    <th>Gastos</th>
                                    <th>Ganancias</th>
                                    <th>Más</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <img src="" alt="Product 1" class="img-circle img-size-32 mr-2">
                                    Some Product
                                </td>
                                <td>$13 USD</td>
                                <td>
                                    <small class="text-success mr-1">
                                    <i class="fas fa-arrow-up"></i>
                                    12%
                                    </small>
                                    12,000 Sold
                                </td>
                                <td>
                                    <a href="#" class="text-muted">
                                    <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="" alt="Product 1" class="img-circle img-size-32 mr-2">
                                    Another Product
                                </td>
                                <td>$29 USD</td>
                                <td>
                                    <small class="text-warning mr-1">
                                    <i class="fas fa-arrow-down"></i>
                                    0.5%
                                    </small>
                                    123,234 Sold
                                </td>
                                <td>
                                    <a href="#" class="text-muted">
                                    <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="" alt="Product 1" class="img-circle img-size-32 mr-2">
                                    Amazing Product
                                    </td>
                                <td>$1,230 USD</td>
                                <td>
                                    <small class="text-danger mr-1">
                                    <i class="fas fa-arrow-down"></i>
                                    3%
                                    </small>
                                    198 Sold
                                </td>
                                <td>
                                    <a href="#" class="text-muted">
                                    <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="" alt="Product 1" class="img-circle img-size-32 mr-2">
                                    Perfect Item
                                    <span class="badge bg-danger">NEW</span>
                                </td>
                                <td>$199 USD</td>
                                <td>
                                    <small class="text-success mr-1">
                                    <i class="fas fa-arrow-up"></i>
                                    63%
                                    </small>
                                    87 Sold
                                </td>
                                <td>
                                    <a href="#" class="text-muted">
                                    <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Ventas</h3>
                                <a href="javascript:void(0);">Ver Reportes</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                            <span class="text-bold text-lg">$18,230.00</span>
                            <span>Ventas del mes</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                            <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 33.1%
                            </span>
                            <span class="text-muted">Since last month</span>
                            </p>
                        </div>
                        <div class="position-relative mb-4">
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>
                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> 2024
                            </span>
                            <span>
                            <i class="fas fa-square text-gray"></i> 2023
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Online Store Overview</h3>
                        <div class="card-tools">
                            <a href="#" class="btn btn-sm btn-tool">
                            <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-tool">
                            <i class="fas fa-bars"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                            <p class="text-success text-xl">
                                <i class="ion ion-ios-refresh-empty"></i>
                            </p>
                            <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                            <i class="ion ion-android-arrow-up text-success"></i> 12%
                            </span>
                            <span class="text-muted">CONVERSION RATE</span>
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                            <p class="text-warning text-xl">
                                <i class="ion ion-ios-cart-outline"></i>
                            </p>
                            <p class="d-flex flex-column text-right">
                                <span class="font-weight-bold">
                                <i class="ion ion-android-arrow-up text-warning"></i> 0.8%
                            </span>
                            <span class="text-muted">SALES RATE</span>
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <p class="text-danger text-xl">
                            <i class="ion ion-ios-people-outline"></i>
                            </p>
                            <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                            <i class="ion ion-android-arrow-down text-danger"></i> 1%
                            </span>
                            <span class="text-muted">REGISTRATION RATE</span>
                            </p>
                        </div>
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>

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
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
@endsection


