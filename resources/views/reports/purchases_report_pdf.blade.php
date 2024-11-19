<!-- resources/views/reports/purchases_report_pdf.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ganancias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        h1, h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Reporte de Ganancias por Compras</h1>
    <h2>Fecha de Inicio: {{ $startDate }} - Fecha de Fin: {{ $endDate }}</h2>

    <h4>Total Ganado: ${{ number_format($totalRevenue, 2) }}</h4>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Descripción</th>
                <th>Paquete</th>
                <th>Monto</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->id }}</td>
                    <td>{{ $purchase->user->name }}</td>
                    <td>{{ $purchase->description }}</td>
                    <td>{{ $purchase->package->name }}</td>
                    <td>${{ number_format($purchase->amount, 2) }}</td>
                    <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
