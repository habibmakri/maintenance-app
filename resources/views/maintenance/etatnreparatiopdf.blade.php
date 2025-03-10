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
            margin-top: 5px;
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
        .header h2 {
            margin: 5px;
        }
    </style>

</head>

<body>
    <div style="margin-bottom: 1%;margin-left: 20%;"class="header">
        <p style="margin: 0px; font-size:12px;">REPUBLIQUE ALGERIENNE DEMOCRATIQUE ET POPULAIRE</p>
        <p style="margin: 0px;font-size:12px;">MINISTERE DES TRANSPORTS</p>
        <p style="margin: 0px;font-size:12px;">ETABLISSEMENT PUBLIC DE TRANSPORT URBAIN ET SUBURBAIN</p>
        <p style="margin: 0px;font-size:12px;">ETUS- SIDI BEL ABBES</p>
        <p style="margin: 0px;font-size:12px;">SERVICE DE LA MAINTENANCE</p>
        <h3 style="margin-left: 15%;">NOMBRE DE RÉPARATION MENSUELLE POUR LE MOIS DE {{ $monthName }}{{ $year }}</h3>
    </div>
    <table style="font-size: 14px;">
        <tr>
            {{-- <th>#</th> --}}
            <th>Bus</th>
            <th>Mécanique</th>
            <th>Eléctrique</th>
            <th>Tole</th>
            <th>Vidange</th>
            <th>Total</th>
        </tr>
        <tbody>
            @php
                $totals = ['E' => 0, 'M' => 0, 'T' => 0, 'V' => 0];
            @endphp
            @foreach ($data as $item)
                <tr>
                    {{-- {{dd($data)}} --}}
                    <td>{{ $item['bus'] }}</td>
                    <td>{{ $item['mecanique'] }}</td>
                    <td>{{ $item['electrique'] }}</td>
                    <td>{{ $item['tole'] }}</td>
                    <td>{{ $item['vidange'] }}</td>
                    <td>{{ $item['mecanique'] + $item['electrique'] + $item['tole'] + $item['vidange'] }}</td>
                </tr>
                @php
                 $totals['M']= $totals['M'] + $item['mecanique']; 
                 $totals['E']= $totals['E'] + $item['electrique']; 
                 $totals['T']= $totals['T'] + $item['tole']; 
                 $totals['V']= $totals['V'] + $item['vidange']; 
                 @endphp
            @endforeach
            <tr>
                {{-- {{dd($data)}} --}}
                <td>Total</td>
                <td>{{ $totals['M'] }}</td>
                <td>{{ $totals['E'] }}</td>
                <td>{{ $totals['T'] }}</td>
                <td>{{ $totals['V'] }}</td>
                <td>{{ $totals['M']+$totals['E']+$totals['T']+$totals['V']}}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
