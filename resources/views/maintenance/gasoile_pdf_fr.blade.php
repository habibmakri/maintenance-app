<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Cambria, serif;
            font-style: italic;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
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
        .header h2 .header h3 {
            margin: 1px;
        }
    </style>

</head>

<body>
    <div style="margin-bottom: 0%;margin-left: 20%;"class="header">
        <p style="margin: 0px; font-size:12px;">REPUBLIQUE ALGERIENNE DEMOCRATIQUE ET POPULAIRE</p>
        <p style="margin: 0px;font-size:12px;">MINISTERE DES TRANSPORTS</p>
        <p style="margin: 0px;font-size:12px;">ETABLISSEMENT PUBLIC DE TRANSPORT URBAIN ET SUBURBAIN</p>
        <p style="margin: 0px;font-size:12px;">ETUS- SIDI BEL ABBES</p>
        <p style="margin: 0px;font-size:12px;">SERVICE DE LA MAINTENANCE</p>
        <h3>Etat de consomation de gasoile</h3>
        <h3>{{ $monthName }} </h3>
    </div>
    <table style="font-size: 13px;">
        @php
        $total = 0;
        @endphp
        <tr>
            <th>Bus</th>
            <th>Gasoile</th>
        </tr>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    {{-- <td>{{ $index + 1 }}</td> --}}
                    <td>{{ $item->bus_name }}</td>
                    <td>{{ $item->total_gasoile }}</td>
                </tr>
                @php
                    $total = $total + $item->total_gasoile;
                    @endphp
            @endforeach
            <tr>
                {{-- <td>{{ $index + 1 }}</td> --}}
                <td>Total</td>
                <td>{{ $total }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
