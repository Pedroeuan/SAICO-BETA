@extends('adminlte::page')
@section('title', 'Nueva Salida')
@section('css')
<style>
    #my-notification .dropdown-menu {
        max-height: 320px;
        width: 360px;
        max-width: 90vw;
        overflow-y: auto;
    }

    #my-notification .dropdown-item {
        white-space: normal;
        word-break: break-word;
    }
</style>
@endsection
@section('content')
<br><br><br>

<div class="container">
    <h4>Nueva salida de vehículo</h4>

    <form method="POST" action="{{ route('salidas.store') }}">
        @csrf

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-2">
            <label>Vehículo</label>
            <select name="vehiculo_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($vehiculos as $vehiculo)
                    <option value="{{ $vehiculo->id }}" {{ old('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                        {{ $vehiculo->placa }} - {{ $vehiculo->marca }}
                    </option>
                @endforeach
            </select>
            @if($vehiculos->isEmpty())
                <small class="text-danger d-block mt-1">
                    No hay vehiculos disponibles con documentacion completa.
                </small>
            @endif
        </div>

        <div class="mb-2">
            <label>Chofer</label>
            <select name="chofer_id" id="chofer_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" data-rol="{{ $usuario->rol }}" data-licencia="{{ $usuario->licencia_vencimiento }}" {{ old('chofer_id') == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->name }}
                    </option>
                @endforeach
            </select>
            <div id="chofer-alert" class="mt-2"></div>
        </div>

        <div class="mb-2">
            <label>Solicitado por</label>
            <select name="solicitado_por" id="solicitado_por" class="form-control" required>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" data-rol="{{ $usuario->rol }}" data-licencia="{{ $usuario->licencia_vencimiento }}" {{ old('solicitado_por') == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->name }}
                    </option>
                @endforeach
            </select>
            <div id="solicitante-alert" class="mt-2"></div>
        </div>

        <div class="mb-2">
            <label>Fecha salida</label>
            <input type="datetime-local" name="fecha_salida" class="form-control" required value="{{ old('fecha_salida', now()->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="mb-2">
            <label>Motivo</label>
            <textarea name="motivo" class="form-control">{{ old('motivo') }}</textarea>
        </div>

        <button class="btn btn-success mt-3" {{ $vehiculos->isEmpty() ? 'disabled' : '' }}>Guardar salida</button>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@stop

@section('js')
<!-- Incluye jQuery-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet"></script>
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet"></script>
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
document.addEventListener('DOMContentLoaded', function () {
    const notificationMenu = document.querySelector('#my-notification .dropdown-menu');
    if (notificationMenu) {
        const normalizeNotificationMenu = () => {
            const items = notificationMenu.querySelectorAll('.dropdown-item');
            items.forEach((item) => {
                const text = (item.textContent || '').trim().toLowerCase();
                if (text === 'todas las notificaciones') {
                    item.textContent = 'Ver todas las notificaciones';
                    item.classList.add('font-weight-bold');
                }
            });
        };

        const observer = new MutationObserver(normalizeNotificationMenu);
        observer.observe(notificationMenu, { childList: true, subtree: true });
        normalizeNotificationMenu();
    }

});
    function formatAlert(message, type = 'danger'){
        return `<div class="alert alert-${type}" role="alert">${message}</div>`;
    }

    function parseDateAsLocal(dateString){
        // Evita desfases por zona horaria al parsear YYYY-MM-DD.
        if (!dateString) return null;
        const onlyDate = String(dateString).trim().slice(0, 10);
        const parts = onlyDate.split('-').map(Number);
        if (parts.length !== 3 || parts.some(Number.isNaN)) return null;
        const [year, month, day] = parts;
        return new Date(year, month - 1, day, 23, 59, 59, 999);
    }

    function checkLicense(licencia, fechaSalida){
        if (!licencia) return {ok:false, text:'Sin fecha de licencia registrada'};
        const vencimiento = parseDateAsLocal(licencia);
        if (!vencimiento || isNaN(vencimiento.getTime())) return {ok:false, text:'Fecha de licencia inválida'};
        const referencia = fechaSalida && !isNaN(fechaSalida.getTime()) ? fechaSalida : new Date();
        return {ok: vencimiento >= referencia, text: vencimiento >= referencia ? 'Licencia vigente' : 'Licencia vencida'};
    }

    document.addEventListener('DOMContentLoaded', function(){
        const choferSelect = document.getElementById('chofer_id');
        const solicitanteSelect = document.getElementById('solicitado_por');
        const fechaSalidaInput = document.querySelector('input[name="fecha_salida"]');
        const choferAlert = document.getElementById('chofer-alert');
        const solicitanteAlert = document.getElementById('solicitante-alert');

        function evalChofer(){
            const opt = choferSelect.selectedOptions[0];
            if (!opt || !opt.value) { choferAlert.innerHTML = ''; return; }
            const licencia = opt.dataset.licencia || '';
            const fechaSalida = fechaSalidaInput && fechaSalidaInput.value ? new Date(fechaSalidaInput.value) : new Date();
            const res = checkLicense(licencia, fechaSalida);
            if (!res.ok) {
                choferAlert.innerHTML = formatAlert(' ' + res.text + '. No recomendable asignar como chofer.');
            } else {
                choferAlert.innerHTML = formatAlert(' ' + res.text, 'success');
            }
        }

        function evalSolicitante(){
            const opt = solicitanteSelect.selectedOptions[0];
            if (!opt || !opt.value) { solicitanteAlert.innerHTML = ''; return; }
            const rol = (opt.dataset.rol || '').toLowerCase();
            // Regla por defecto: usuarios con rol 'cliente' no pueden solicitar
            if (rol === 'cliente') {
                solicitanteAlert.innerHTML = formatAlert(' Este usuario no tiene permiso para solicitar vehículos.');
            } else {
                solicitanteAlert.innerHTML = formatAlert(' Este usuario puede solicitar vehículos.', 'success');
            }
        }

        choferSelect.addEventListener('change', evalChofer);
        solicitanteSelect.addEventListener('change', evalSolicitante);
        if (fechaSalidaInput) {
            fechaSalidaInput.addEventListener('change', evalChofer);
        }

        // evaluar estado inicial si ya hay selección
        evalChofer();
        evalSolicitante();
    });
</script>

@endsection
