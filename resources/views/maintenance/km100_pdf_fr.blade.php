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
        .header h2 
        .header h3 {
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
        <h3>Etat de consomation de gasoile au 100 KM</h3>
        @if ($datedupdf == $dateaupdf)
            <h3>Le {{ $datedupdf }} </h3>
        @else
            <h3>Période du {{ $datedupdf }} au {{ $dateaupdf }} </h3>
        @endif
    </div>
    <table style="font-size: 13px;">
        <tr>
            {{-- <th>#</th> --}}
            <th>Bus</th>
            <th>Kilométrage</th>
            <th>Gasoile</th>
            <th>Cons/100</th>
        </tr>
        <tbody>
            @php
                $totals = ['KM' => 0, 'GAS' => 0];
            @endphp
            @foreach ($data as $index => $item)
                <tr>
                    {{-- <td>{{ $index + 1 }}</td> --}}
                    <td>{{ $item->bus_name }}</td>
                    <td>{{ $item->total_km }}</td>
                    <td>{{ $item->total_gasoile }}</td>
                    <td>
                        @if ($item->total_km > 0)
                            {{ number_format(($item->total_gasoile * 100) / $item->total_km, 2) }}
                        @else
                            0
                        @endif
                    </td>
                </tr>
                @php
                 $totals['KM']= $totals['KM'] + $item->total_km; 
                 $totals['GAS']= $totals['GAS'] + $item->total_gasoile; 
                 @endphp
            @endforeach
            <tr>
                {{-- {{dd($data)}} --}}
                <td>Total</td>
                <td>{{ $totals['KM'] }}</td>
                <td>{{ $totals['GAS'] }}</td>
                <td>{{ number_format(($totals['GAS'] * 100) / $totals['KM'], 2)}}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
