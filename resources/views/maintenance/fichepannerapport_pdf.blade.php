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
        <h1>Rapport du Panne</h1>
        <h2>Bus {{ $fichepanne->fichemaintenance->bus->name }} - {{ $fichepanne->pannename->name }}</h2>
    </div>

    <table class="details-table">
        <tr>
            <td><strong>Bus:</strong> {{ $fichepanne->fichemaintenance->bus->name }}</td>
            <td><strong>Panne:</strong> {{ $fichepanne->pannename->name }}</td>
            <td><strong>Type:</strong> {{ $fichepanne->pannename->type }}</td>
        </tr>
        <tr>
            <td><strong>Etat:</strong>
                @if ($fichepanne->solved == true)
                    Résolue
                @endif
            </td>
            <td colspan="2"><strong>Déclaré le:</strong> {{ $fichepanne->fichemaintenance->date_fiche }} -
                {{ $fichepanne->fichemaintenance->brigade }}</td>
            <td colspan="2"><strong>Déclaré par:</strong> @if ($fichepanne->fichemaintenance->chauffeur) {{ $fichepanne->fichemaintenance->chauffeur->fr_name }} @else Equipe Maintenance @endif  </td>
        </tr>
        <tr>
            <td><strong>Résolue le:</strong> {{ $fichepanne->date_resoudre }} - {{ $fichepanne->brigade }}</td>
            <td colspan="2"><strong>Equipe:</strong>
                @if($fichepanne->equipe)
                @foreach (json_decode($fichepanne->equipe, true) as $equipe)
                {{ $equipe }}@if (!$loop->last)
                ,
                @endif
                @endforeach
                @endif
            </td>
            <td colspan="3"><strong>Lieu:</strong>{{$fichepanne->lieu_resoudre}}</td>
        </tr>
    </table>

    <div style="margin-bottom: 20px;">
        <strong>Description:</strong>
        <p>{{ $fichepanne->description }}</p>
    </div>

    <p><strong>Pieces utilisé:</strong></p>
    <table>
        <thead>
            <tr>
                <th>Pièce</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fichepanne->used_pieces as $piece)
                <tr>
                    <td>{{ $piece->piece->name }}</td>
                    <td>{{ $piece->quantité }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
