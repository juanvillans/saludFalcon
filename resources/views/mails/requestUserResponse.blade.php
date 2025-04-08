<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuesta a tu solicitud</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
        }
        .content {
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 15px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo de la empresa" class="logo" data-skip-tracking="true">
        @endif
        <h2>Respuesta a tu solicitud</h2>
    </div>

    <div class="content">
        <p>Hola {{ $userData['name'] }},</p>
        
        <p>Hemos procesado tu solicitud de ingreso a emergencia con los siguientes datos:</p>
        
        <ul>
            <li><strong>Número de solicitud:</strong> {{ $userData['id'] }}</li>
            <li><strong>Fecha:</strong> {{ $responseDate }}</li>
        </ul>

        <p>El estado actual de tu solicitud es: <strong>{{ $status?'Aceptada':'Rechazada' }}</strong></p>

        @if($status)
            <p>Puedes ingresar, con su cédula haciendo clic en el siguiente botón :</p>
            <a href="{{ url('/') }}" class="button">Ingresar</a>
            <p>O copia esta URL en tu navegador:<br>
            <small>{{ url('/') }}</small></p>
        @endif

        <p>Si tienes alguna pregunta, no dudes en contactarnos por: <strong>ostisaludfalcon@gmail.com</strong>.</p>
        
        <p>Atentamente,<br>
        El equipo de Sistemas</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Secretaria de Salud. Todos los derechos reservados.</p>
        {{-- <p>
            <a href="{{ url('/politica-privacidad') }}">Política de Privacidad</a> | 
            <a href="{{ url('/contacto') }}">Contacto</a>
        </p> --}}
        <p>Si no solicitaste este correo, por favor ignóralo.</p>
    </div>
</body>
</html>