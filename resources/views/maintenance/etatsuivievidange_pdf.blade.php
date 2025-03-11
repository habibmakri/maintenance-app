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
        <h3>FICHE DE SUIVI VIDANGE</h3>
        <h2>{{ $monthName }}</h2>
    </div>
    @php
        $totals = ['MOTEUR' => 0, 'BOITE' => 0, 'POND' => 0];
    @endphp
    @foreach ($groupedtotal as $date => $pannes)
        <h5 style="margin: 5px;">DATE: {{ $date }}</h5>
        <table style="font-size:10px;">
            <thead>
                <tr>
                    <td style="font-weight: bold;width:10%">Date</td>
                    <td style="font-weight: bold;width:5%">Bus</td>
                    <td style="font-weight: bold;width:45%">Panne/Travaux Effectués</td>
                    <td style="font-weight: bold;width:15%">Equipe</td>
                    <td style="font-weight: bold;width:10%">Moment/Lieu de l'opération</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($pannes as $panne)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($panne['date'])->format('d/m/Y') }}</td>
                        <td>{{ $panne['bus'] }}</td>
                        <td style="text-align: left; text-align:justify;padding:5px;"><span
                                style="text-decoration: underline;"><strong>{{  $panne['name'] }}</strong></span><br>{{ $panne['description'] }}<br>
                            @foreach ($panne['used_pieces'] as $piece)
                                {{ $piece->piece->name }} => {{ $piece->quantité }} ||
                            @endforeach
                        </td>
                        @if (!is_null($panne['equipe']))
                            <td style="text-align: left;">
                                @foreach (json_decode($panne['equipe'], true) as $equipe)
                                    {{ $equipe }}@if (!$loop->last)
                                        ,<br>
                                    @endif
                                @endforeach
                            </td>
                            @else
                            <td style="text-align: left;">

                            </td>
                        @endif
                        <td><span
                                style="font-weight: bold">{{ ucfirst($panne['brigade']) }}</span><br>{{ $panne['lieu'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @foreach ($pannes as $panne)
            @if ($panne['name'] === 'Vidange moteur')
                @php $totals['MOTEUR']++; @endphp
            @endif
            @if ($panne['name'] === 'Vidange boite vitesse')
                @php $totals['BOITE']++; @endphp
            @endif
            @if ($panne['name'] === 'Vidange pond')
                @php $totals['POND']++; @endphp
            @endif
        @endforeach
    @endforeach
    <h5 style="margin: 5px;">{{ $monthName }}</h5>
    <table style="font-size:10px;">
        <thead>
            <tr>
                <th colspan="3">Nombre Totale des Vidange</th>
            </tr>
            <tr>
                <th style="text-align: center;width:33%;">Moteur</th>
                <th style="text-align: center;width:33%;">Boite Vitesse</th>
                <th style="text-align: center;width:33%;">Pond</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;width:33%;">{{ $totals['MOTEUR'] }}</td>
                <td style="text-align: center;width:33%;">{{ $totals['BOITE'] }}</td>
                <td style="text-align: center;width:33%;">{{ $totals['POND'] }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
