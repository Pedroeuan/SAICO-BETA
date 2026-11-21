<script>
// Mismo botón y comportamiento de navegación usados en los formularios IM.
document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('F-SGLE-708-02-PT');
    if (!formulario || document.getElementById('navegacion-formulario-0402')) return;

    const boton = document.createElement('button');
    boton.id = 'navegacion-formulario-0402';
    boton.type = 'button';
    boton.className = 'btn btn-primary shadow';
    boton.style.cssText = 'position:fixed;z-index:1030;width:42px;height:42px;border-radius:50%;font-size:25px;line-height:1;padding:0;';
    document.body.appendChild(boton);

    const estilo = document.createElement('style');
    estilo.textContent = '@media print { #navegacion-formulario-0402 { display:none !important; } }';
    document.head.appendChild(estilo);

    let subir = false;
    function actualizar() {
        const caja = formulario.getBoundingClientRect();
        const borde = (formulario.closest('.card') || formulario).getBoundingClientRect();
        const limiteDerecho = document.documentElement.clientWidth - 50;
        boton.style.left = Math.max(8, Math.min(borde.right + 10, limiteDerecho)) + 'px';
        boton.style.top = Math.max(100, Math.min(borde.top + 16, window.innerHeight - 58)) + 'px';
        const recorrido = Math.max(0, caja.height - window.innerHeight);
        subir = recorrido > 0 && -caja.top > recorrido / 2;
        boton.textContent = subir ? '↑' : '↓';
        const etiqueta = (subir ? 'Ir al inicio del ' : 'Ir al final del ') + 'formulario';
        boton.title = etiqueta;
        boton.setAttribute('aria-label', etiqueta);
    }

    boton.addEventListener('click', function () {
        const caja = formulario.getBoundingClientRect();
        const destino = window.scrollY + (subir ? caja.top - 70 : caja.bottom - window.innerHeight + 20);
        const reducirMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        window.scrollTo({top: Math.max(0, destino), behavior: reducirMovimiento ? 'auto' : 'smooth'});
    });
    window.addEventListener('scroll', actualizar, {passive: true});
    window.addEventListener('resize', actualizar);
    if (window.ResizeObserver) new ResizeObserver(actualizar).observe(formulario);
    actualizar();
});
</script>
