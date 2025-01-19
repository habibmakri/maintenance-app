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
            margin-top: 20px;
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
        <p style="margin: 0px; font-size:12px;">REPUBLIQUE  ALGERIENNE DEMOCRATIQUE ET POPULAIRE</p>
        <p style="margin: 0px;font-size:12px;">MINISTERE DES TRANSPORTS</p>
        <p style="margin: 0px;font-size:12px;">ETABLISSEMENT PUBLIC DE TRANSPORT URBAIN ET SUBURBAIN</p>
        <p style="margin: 0px;font-size:12px;">ETUS- SIDI BEL ABBES</p>
        <p style="margin: 0px;font-size:12px;">SERVICE DE LA MAINTENANCE</p>
        <h3>FICHE DE SUIVI MENSUELLE DES TRAVAUX REPARÉS</h3>
        <h2>Bus {{ $bus->name }} - {{ $monthName }}</h2>
    </div>

    <table style="font-size:10px;">
        <thead>
            <tr>
                <td style="font-weight: bold">Date</td>
                <td style="font-weight: bold">Bus</td>
                <td style="font-weight: bold">Panne/Travaux Effectués</td>
                <td style="font-weight: bold">Nature de Panne</td>
                <td style="font-weight: bold">Equipe</td>
                <td style="font-weight: bold">Moment/Lieu de l'opération</td>
            </tr>
        </thead>
        <tbody>
            @php
                $totals = ['E' => 0, 'M' => 0, 'T' => 0,'V'=> 0];
            @endphp
            @foreach ($pannes as $panne)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($panne['date'])->format('d/m/Y') }}</td>
                    <td>{{ $bus->name }}</td>
                    <td style="text-align: left; text-align:justify;padding:5px;"><span
                            style="text-decoration: underline;"><strong>{{ $panne['name'] }}</strong></span><br>{{ $panne['description'] }}<br>
                        @foreach ($panne['used_pieces'] as $piece)
                            {{ $piece->piece->name }} => {{ $piece->quantité }} ||
                        @endforeach
                    </td>
                    <td>
                        {{ ucfirst($panne['type']) }}
                    </td>
                    <td style="text-align: left;">
                        @foreach (json_decode($panne['equipe'], true) as $equipe)
                            {{ $equipe }}@if (!$loop->last)
                                ,<br>
                            @endif
                        @endforeach
                    </td>
                    <td><span
                            style="font-weight: bold">{{ ucfirst($panne['brigade']) }}</span><br>{{ $panne['lieu'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="font-size:10px;">
        <thead>
            <tr>
                <th colspan="4">Nombre Totale des pannes</th>
            </tr>
            <tr>
                <th style="text-align: center;width:33%;">Mecanique</th>
                <th style="text-align: center;width:33%;">Electrique</th>
                <th style="text-align: center;width:33%;">Tolle</th>
                <th style="text-align: center;width:33%;">Vidange</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totals = ['E' => 0, 'M' => 0, 'T' => 0,'V'=> 0];
            @endphp
            @foreach ($pannes as $panne)
                @if ($panne['type'] === 'mecanique'||$panne['type'] === 'jauge')
                    @php $totals['M']++; @endphp
                @endif
                @if ($panne['type'] === 'electrique')
                    @php $totals['E']++; @endphp
                @endif
                @if ($panne['type'] === 'tolle')
                    @php $totals['T']++; @endphp
                @endif
                @if ($panne['type'] === 'vidange')
                    @php $totals['V']++; @endphp
                @endif
            @endforeach
            <tr>
                <td style="text-align: center;width:33%;">{{ $totals['M'] }}</td>
                <td style="text-align: center;width:33%;">{{ $totals['E'] }}</td>
                <td style="text-align: center;width:33%;">{{ $totals['T'] }}</td>
                <td style="text-align: center;width:33%;">{{ $totals['V'] }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
