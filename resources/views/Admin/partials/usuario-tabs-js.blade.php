<script>
(() => {
    const form = document.getElementById('UsuarioForm');
    const tabs = [...form.querySelectorAll('[data-usuario-tab]')];
    const activar = (id, enfocar = false) => {
        tabs.forEach(tab => {
            const activo = tab.dataset.usuarioTab === id;
            tab.classList.toggle('active', activo);
            tab.setAttribute('aria-selected', String(activo));
            tab.tabIndex = activo ? 0 : -1;
            document.getElementById(tab.dataset.usuarioTab).hidden = !activo;
            if (activo && enfocar) tab.focus();
        });
    };
    tabs.forEach((tab, indice) => {
        tab.addEventListener('click', () => activar(tab.dataset.usuarioTab));
        tab.addEventListener('keydown', event => {
            if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
            event.preventDefault();
            let siguiente = event.key === 'Home' ? 0 : event.key === 'End' ? tabs.length - 1
                : (indice + (event.key === 'ArrowRight' ? 1 : -1) + tabs.length) % tabs.length;
            activar(tabs[siguiente].dataset.usuarioTab, true);
        });
    });
    // Mostrar el campo inválido aunque esté en otra pestaña.
    form.addEventListener('invalid', event => {
        if (event.target !== form.querySelector(':invalid')) return;
        const panel = event.target.closest('.usuario-panel');
        if (panel) activar(panel.id);
    }, true);
    const error = form.querySelector('.is-invalid, .text-danger');
    const panelError = error?.closest('.usuario-panel');
    if (panelError) activar(panelError.id);
})();
</script>
