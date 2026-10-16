@if($errors->any())
    <div class="alert alert-danger" role="alert" tabindex="-1" id="errores-guardado-0402">
        <strong>No se guardó el reporte. Revisa estos datos:</strong>
        <ul class="mb-0">@foreach($errors->all() as $mensaje)<li>{{ $mensaje }}</li>@endforeach</ul>
    </div>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('FOR-PIMP-04_02');
    if (!form) return;
    let aviso = document.getElementById('errores-guardado-0402');
    if (aviso) { aviso.scrollIntoView({ block: 'center' }); aviso.focus(); }
    form.addEventListener('invalid', function (event) {
        const campo = event.target;
        const seccion = campo.closest('.saico-form-section');
        if (seccion?.classList.contains('is-collapsed')) seccion.querySelector('.saico-form-section-header')?.click();
        if (!aviso) {
            aviso = document.createElement('div'); aviso.className = 'alert alert-danger'; aviso.setAttribute('role', 'alert'); form.prepend(aviso);
        }
        const etiqueta = campo.id ? [...form.querySelectorAll('label')].find(label => label.htmlFor === campo.id)?.textContent.trim() : null;
        aviso.textContent = 'No se envió el reporte. ' + (etiqueta || campo.getAttribute('aria-label') || campo.name || 'Campo pendiente') + ': ' + campo.validationMessage;
    }, true);
});
</script>
