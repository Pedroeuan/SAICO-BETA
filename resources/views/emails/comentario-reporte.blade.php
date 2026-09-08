<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $asunto }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border: 1px solid #003b80; margin: 0 auto;">
        <tr style="background-color: #003b80; color: #ffffff;">
            <td style="padding: 15px; text-align: center;">
                @if(isset($message))
                    <img src="{{ $message->embed($logoPath) }}" alt="SAICO" width="150" height="50" style="display: block; margin: 0 auto;">
                @else
                    <img src="{{ asset('images/saico3.png') }}" alt="SAICO" width="150" height="50" style="display: block; margin: 0 auto;">
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; color: #333333;">
                <h2 style="color: #003b80;">Hola {{ $nombreDestinatario }}</h2>
                <p><strong>Asunto: </strong><span style="color: #E01A22;">{{ $asunto }}</span></p>
                <p>{!! $mensaje_email !!}</p>
                <p style="text-align: center; margin: 20px 0;">
                    <a href="{{ $url }}" style="background-color: #003b80; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Ver reporte</a>
                </p>
                <p style="font-size: 12px; color: #888888;">Este es un aviso automático. No responder a este correo.</p>
            </td>
        </tr>
    </table>
</body>
</html>
