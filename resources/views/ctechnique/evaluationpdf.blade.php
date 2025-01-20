<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .details-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .details-table td {
            padding: 10px;
            border: none;
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1,
        .header h2 {
            margin: 5px;
        }
    </style>
</head>

<body>
    <div style="margin-bottom: 13%;"class="header">
        <h1>Evaluation</h1>
        <h2>{{ $rating->created_at }}</h2>
    </div>
    <center>
        <h2>Détails:</h2>
    </center>
    <table class="details-table">
        <tr>
            <td><strong>Service:</strong> {{ $rating->service }}</td>
            <td><strong>Controlleur:</strong> {{ $rating->controler }}</td>
            <td><strong>Propreté:</strong> {{ $rating->clean }}</td>
            <td><strong>Gestion:</strong> {{ $rating->order }}</td>
        </tr>
        <tr>
            <td><strong>N° Telephone:</strong>
                {{ $rating->phone }}
            </td>
        </tr>
        <tr>
            <td><strong>Message:</strong>
                {{ $rating->message }}
            </td>
        </tr>
    </table>
</body>

</html>
