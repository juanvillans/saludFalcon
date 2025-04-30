<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Cita Médica</title>
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
        .button-cancel {
            background-color: #e3342f;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .appointment-details {
            background-color: #fff;
            border-left: 4px solid #3490dc;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="Logo de la empresa" class="logo" data-skip-tracking="true">
        @endif
        <h2>Confirmación de Cita Médica</h2>
    </div>

    <div class="content">
        <p>Hola {{ $appointment->appointment_data['name'] }},</p>
        
        <p>Tu cita médica ha sido registrada exitosamente con los siguientes detalles:</p>
        
        <div class="appointment-details">
            <ul>
                <li><strong>Especialidad:</strong> {{ $calendar->specialty->name }}</li>
                <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($appointment->day_reserved)->format('d/m/Y') }}</li>
                <li><strong>Hora:</strong> {{ $appointment->time_reserved }}</li>
            </ul>
        </div>

        <p>Recibirás un recordatorio un día antes de tu cita.</p>

        <p>Si necesitas cancelar o reprogramar tu cita, puedes hacerlo haciendo clic en el siguiente botón:</p>
        <a href="{{ route('agenda.cancel-appointment-patient', ['token' => $appointment->token]) }}" class="button button-cancel">Cancelar Cita</a>
        
        <p>O copia esta URL en tu navegador:<br>
        <small>{{ route('agenda.cancel-appointment-patient', ['token' => $appointment->token ]) }}</small></p>

        <p>Para cualquier duda o información adicional, puedes contactarnos al correo: <strong>ostisaludfalcon@gmail.com</strong>.</p>
        
        <p>Atentamente,<br>
        El equipo de {{ config('app.name') }}</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Secretaria de Salud. Todos los derechos reservados.</p>
        <p>Si no solicitaste esta cita, por favor contacta a nuestro equipo inmediatamente.</p>
    </div>
</body>
</html>