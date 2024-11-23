<!-- Header con tabla -->
<table style="width: 100%; border: none;">
    <tr>
        <td style="width: 50%; text-align: left;">
            <img src="{{ public_path('images/Logo_AICO_R.jpg') }}" alt="Logo" style="height: 50px;">
        </td>
        <td style="width: 50%; text-align: right;">
            <h3>Reporte FOR-PINS-03/01</h3>
            <p>Fecha: {{ date('d-m-Y') }}</p>
        </td>
    </tr>
</table>

<!-- Contenido del reporte -->
<div style="margin-top: 20px;">
@for($i = 0; $i < 1024; $i++)
HOLA MUNDO
@endfor
</div>

<!-- Footer -->
<div style="position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px;">
    <p>Este es el footer - Página {PAGE_NUM} de {PAGE_COUNT}</p>
</div>
