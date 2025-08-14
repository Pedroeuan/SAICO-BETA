<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $mensajeCorto }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
        <tr style="background-color: #2c3e50; color: #ffffff;">
            <td style="padding: 15px; text-align: center;">
                <img src="{{ asset('images/saico.png') }}" alt="SAICO" style="height: 50px;">
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <h2 style="color: #2c3e50;">Hola {{ $usuario->name }} 👋</h2>
                <p>{{ $mensajeLargo }}</p>
                <p><strong>Asunto:</strong> {{ $mensajeCorto }}</p>
                @if(isset($fechaCalibracion))
                <p><strong>Fecha de vencimiento:</strong> {{ $fechaCalibracion }}</p>
                @endif
                <p>Por favor revisa esta información a la brevedad.</p>
                <p style="font-size: 12px; color: #888;">Este es un aviso automático. No responder a este correo.</p>
            </td>
        </tr>
        <tr style="background-color: #f1f1f1; color: #555;">
            <td style="padding: 10px; text-align: center; font-size: 12px;">
                SAICO - ASESORIA E INSPECCIÓN EN CONSTRUCCIÓN COSTA FUERA
            </td>
        </tr>
    </table>
</body>
</html>
