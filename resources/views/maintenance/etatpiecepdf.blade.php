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
    <div style="margin-bottom: 7%;margin-left: 20%;"class="header">
        <p style="margin: 0px; font-size:12px;">REPUBLIQUE ALGERIENNE DEMOCRATIQUE ET POPULAIRE</p>
        <p style="margin: 0px;font-size:12px;">MINISTERE DES TRANSPORTS</p>
        <p style="margin: 0px;font-size:12px;">ETABLISSEMENT PUBLIC DE TRANSPORT URBAIN ET SUBURBAIN</p>
        <p style="margin: 0px;font-size:12px;">ETUS- SIDI BEL ABBES</p>
        <p style="margin: 0px;font-size:12px;">SERVICE DE LA MAINTENANCE</p>
        <h3>Etat de consomation du {{$piecename}}</h3>
        <h2>{{ $monthName }}</h2>
    </div>
        @php
            $totalequantitetotale = 0;
            @endphp
    @foreach ($groupedtotal as $bus => $pieces)
    @php
            $quantitetotale = 0;
            @endphp
        <h5 style="margin: 5px;">Bus: {{ $bus }}</h5>
        <table style="font-size:10px;">
            <thead>
                <tr>
                    <td style="font-weight: bold;width:10%">Bus</td>
                    <td style="font-weight: bold;width:15%">Date</td>
                    <td style="font-weight: bold;width:15%">Piece</td>
                    <td style="font-weight: bold;width:15%">Quantite</td>
                    <td style="font-weight: bold;width:15%">Traveaux</td>
                    <td style="font-weight: bold;width:45%">Equipe</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($pieces as $piece)
                    <tr>
                        <td>{{ $piece['bus'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($piece['date'])->format('d/m/Y') }}</td>
                        <td style="text-align: left; text-align:justify;padding:5px;"><span
                                style="text-decoration: underline;"><strong>{{ $piece['name'] }}</strong></span></td>
                        <td>
                            {{ $piece['quantite'] }}
                            @php $quantitetotale=$quantitetotale+$piece['quantite']; @endphp
                            @php $totalequantitetotale = $totalequantitetotale+$piece['quantite']; @endphp
                        </td>
                        <td>
                            {{ $piece['panne'] }}
                        </td>
                        @if (!is_null($piece['equipe']))
                            <td style="text-align: left;">
                                @foreach (json_decode($piece['equipe'], true) as $equipe)
                                    {{ $equipe }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                        @else
                            <td style="text-align: left;">

                            </td>
                        @endif
                    </tr>
                    @endforeach
                    <tr>
                        <td style="border: none"></td>
                        <td style="border: none"></td>
                        <td style="text-align: center; text-align:justify;padding:5px;"><strong>Total:</strong></td>
                        <td>
                            {{ $quantitetotale }}
                        </td>
                        <td style="border: none"></td>
                    </tr>
                </tbody>
            </table>
    @endforeach
    <h5 style="margin: 5px;">{{ $monthName }}</h5>
    <table style="font-size:10px;">
        <thead>
            <tr>
                <th colspan="2">Quantite Totale</th>
            </tr>
            <tr>
                <th style="text-align: center;width:33%;">{{$piecename}}</th>
                <td style="text-align: center;width:33%;">{{ $totalequantitetotale }}</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</body>

</html>
