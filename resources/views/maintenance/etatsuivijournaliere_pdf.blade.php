<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
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
    <div style="margin-bottom: 13%;margin-left: 20%;"class="header">
        <h3>FICHE DE SUIVI JOURNALIERE DES TRAVAUX REPARÉS</h3>
        <h2>{{ $monthName }}</h2>
    </div>
    @php
        $totals = ['E' => 0, 'M' => 0, 'T' => 0];
    @endphp
    @foreach ($groupedpannes as $date => $pannes)
        <h5 style="margin: 5px;">DATE: {{ $date }}</h5>
        <table style="font-size:10px;">
            <thead>
                <tr>
                    <td style="font-weight: bold;width:10%">Date</td>
                    <td style="font-weight: bold;width:5%">Bus</td>
                    <td style="font-weight: bold;width:45%">Panne/Travaux Effectués</td>
                    <td style="font-weight: bold;width:10%">Nature de Panne</td>
                    <td style="font-weight: bold;width:15%">Equipe</td>
                    <td style="font-weight: bold;width:10%">Moment/Lieu de l'opération</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($pannes as $panne)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($panne->date_resoudre)->format('d/m/Y') }}</td>
                        <td>{{ $panne->fichemaintenance->bus->name }}</td>
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
        @endforeach
    @endforeach
    <h5 style="margin: 5px;">{{ $monthName }}</h5>
    <table style="font-size:10px;">
        <thead>
            <tr>
                <th colspan="3">Nombre Totale des pannes</th>
            </tr>
            <tr>
                <th style="text-align: center;width:33%;">Mecanique</th>
                <th style="text-align: center;width:33%;">Electrique</th>
                <th style="text-align: center;width:33%;">Tolle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;width:33%;">{{ $totals['M'] }}</td>
                <td style="text-align: center;width:33%;">{{ $totals['E'] }}</td>
                <td style="text-align: center;width:33%;">{{ $totals['T'] }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
