<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fafafa;
        }
        .details p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }
        .details strong {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ticket de Reserva</h1>
        <div class="details">
            <p><strong>Gracias:</strong> {{ $user->name }}</p>
            <p><strong>Número de Plaza:</strong> {{ $plaza->id }}</p> <!-- Asegúrate de que el campo correcto se llame 'number' -->
            <p><strong>Fecha de Reserva:</strong> {{ $reservation->reservation_date }}</p>
            <p><strong>Hora de Inicio:</strong> {{ $reservation->start_time }}:00</p>
            <p><strong>Hora de Fin:</strong> {{ $reservation->end_time }}</p>
        </div>
        <p class="footer">Gracias por reservar con nosotros.</p>
    </div>
</body>
</html>
