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
    <div style="margin-bottom: 13%;margin-left: 20%;"class="header">
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
                    <td>{{ \Carbon\Carbon::parse($panne->date_resoudre)->format('d/m/Y') }}</td>
                    <td>{{ $bus->name }}</td>
                    <td style="text-align: left; text-align:justify;padding:5px;"><span
                            style="text-decoration: underline;"><strong>{{ $panne->pannename->name }}</strong></span><br>{{ $panne->description }}<br>
                        @foreach ($panne->used_pieces as $piece)
                            {{ $piece->piece->name }} => {{ $piece->quantité }} ||
                        @endforeach
                    </td>
                    <td>
                        {{ ucfirst($panne->pannename->type) }}
                    </td>
                    <td style="text-align: left;">
                        @foreach (json_decode($panne->equipe, true) as $equipe)
                            {{ $equipe }}@if (!$loop->last)
                                ,<br>
                            @endif
                        @endforeach
                    </td>
                    <td><span
                            style="font-weight: bold">{{ ucfirst($panne->brigade) }}</span><br>{{ $panne->lieu_resoudre }}
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
                @if ($panne->pannename->type === 'mecanique')
                    @php $totals['M']++; @endphp
                @endif
                @if ($panne->pannename->type === 'electrique')
                    @php $totals['E']++; @endphp
                @endif
                @if ($panne->pannename->type === 'tolle')
                    @php $totals['T']++; @endphp
                @endif
                @if ($panne->pannename->type === 'vidange')
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
