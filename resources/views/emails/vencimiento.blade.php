<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $mensajeCorto }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">
    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #003b80; margin: 0 auto;">
        <tr style="background-color: #003b80; color: #ffffff;">
            <td style="padding: 15px; text-align: center;">
                <img src="{{ $message->embed($logoPath) }}" 
                    alt="SAICO" 
                    width="150" 
                    height="50" 
                    style="display: block; margin: 0 auto;">
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <h2 style="color: #003b80;">Hola {{ $usuario->name }} 👋</h2>
                <p><strong>Asunto: </strong><span style="color: #E01A22;">{{ $mensajeCorto }}</span></p>
                <p>{!! $mensajeLargo !!}</p>
                @if(isset($fechaCalibracion))
                <p><strong>Fecha de vencimiento:</strong><span style="color: #E01A22;"> {{ $fechaCalibracion }}</span></p>
                @endif
                <p>Por favor revisa esta información a la brevedad.</p>
                @if(isset($url))
                    <p style="text-align: center; margin: 20px 0;">
                        <a href="{{ $url }}" 
                        style="background-color: #003b80; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                        Ver Detalles
                        </a>
                    </p>
                @endif
                <p style="font-size: 12px; color: #888;">Este es un aviso automático. No responder a este correo.</p>
            </td>
        </tr>
        <tr style="background-color: #f1f1f1; color: #555;">
            <td style="padding: 10px; text-align: center; font-size: 12px;">
                <span style="color: #003b80;">S</span><span style="color: #E01A22;">ISTEMA</span> - 
                <span style="color: #E01A22;">A</span>SESORIA E 
                <span style="color: #E01A22;">I</span>NSPECCIÓN EN CONSTRUCCIÓN 
                <span style="color: #E01A22;">CO</span>STA FUERA
            </td>
        </tr>
    </table>
</body>
</html>
