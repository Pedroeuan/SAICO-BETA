    $(document).ready(function() {
        function setDisponibilidadBgColor(select) {
            var val = $(select).val();
            var bg = '';
            var color = 'white';
            // Solo para opciones del laboratorio
            if (val === 'Equipo Disponible' || val === 'Nuevo') {
                bg = '#28a745'; // verde
                color = 'white';
            } else if (val === 'Equipo Fuera de Servicio' || val === 'Usado') {
                bg = '#eeff07ff'; // amarillo
                color = 'black';
            } else if (val === 'En Servicio') {
                bg = '#dca735'; // naranja
                color = 'white';
            }else if (val === 'Equipo en Resguardo' || val === 'Terminado') {
                bg = '#dc3545'; // rojo
                color = 'white';
            } else {
                bg = 'white';
                color = 'black';
            }
            $(select).css({
                'background-color': bg,
                'color': color
            });
        }

        $('select[name="Disponibilidad_Estado"]').each(function() {
            setDisponibilidadBgColor(this);
        }).on('change', function() {
            setDisponibilidadBgColor(this);
        });
    });

/*PREVENIR Enters*/
function prevenirEnter(formId) {
    document.getElementById(formId).addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
            event.preventDefault();
        }
    });
}

/*Prevenir el Enter Equipos*/
prevenirEnter('equiposForm');

    /*Prevenir el Enter Consumibles*/
prevenirEnter('consumiblesForm');

    /*Prevenir el Enter Accesorios*/
prevenirEnter('accesoriosForm');

    /*Prevenir el Enter Blocks*/
prevenirEnter('blocksForm');

    /*Prevenir el Enter Herramientas*/
prevenirEnter('herramientasForm');

    /*Prevenir el Enter Kits*/
prevenirEnter('kitForm');

    document.addEventListener('DOMContentLoaded', function() {
        const stockTotal = document.getElementById('stockTotal');
        const stockUsado = document.getElementById('stockUsado');
        const stockNuevo = document.getElementById('stockNuevo');

        function updateStock(source) {
            const total = parseInt(stockTotal.value) || 0;
                                                    
            if (source === 'usado') {
            const usado = parseInt(stockUsado.value) || 0;
            if (usado <= total) {
                stockNuevo.value = total - usado;
            } else {
                stockUsado.value = total;
                stockNuevo.value = 0;
            }
            } else if (source === 'nuevo') {
            const nuevo = parseInt(stockNuevo.value) || 0;
            if (nuevo <= total) {
                stockUsado.value = total - nuevo;
            } else {
                stockNuevo.value = total;
                stockUsado.value = 0;
            }
            } else if (source === 'total') {
            const usado = parseInt(stockUsado.value) || 0;
            if (usado <= total) {
                stockNuevo.value = total - usado;
            } else {
                stockUsado.value = total;
                stockNuevo.value = 0;
            }
            }
            }

            stockTotal.addEventListener('input', () => updateStock('total'));
            stockUsado.addEventListener('input', () => updateStock('usado'));
            stockNuevo.addEventListener('input', () => updateStock('nuevo'));
    });