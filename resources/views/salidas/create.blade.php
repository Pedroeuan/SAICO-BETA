@extends('adminlte::page')
@section('title', 'Nueva Salida')
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

        <button class="btn btn-success mt-3">Guardar salida</button>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection

@section('js')
<script>
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

