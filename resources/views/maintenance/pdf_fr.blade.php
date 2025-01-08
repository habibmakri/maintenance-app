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
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        /* h1{
            text-align: center;
        } */
        @page {
            footer: myfooter;
        }
        @page myfooter {
            content: "Page {PAGENO} of {nbpg}";
            text-align: center;
            font-size: 12px;
        }
    </style>
    
</head>
<body>
    <div style="text-align: center;">
        <h1>Liste d'étaillé du Maintenance des Bus</h1>
        @if ($datedupdf == $dateaupdf)
        <h2 >Le {{ $datedupdf }} | {{$brigadepdf}}</h2>
        @else
        <h2 >Période du {{ $datedupdf }} au {{ $dateaupdf }} | {{$brigadepdf}}</h2>
        @endif
    </div>

    <table style="font-size: 14px;">
        {{-- <thead > --}}
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Bus</th>
                <th>Brigade</th>
                <th>chauffeur</th>
                <th>Ligne</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Gasoile</th>
                <th>KM-<br/>Depart</th>
                <th>KM-<br/>Arrivée</th>
                <th>KM-<br/>Globale</th>
                <th>KM-<br/>HLP</th>
                <th>KM-<br/>Commerciale</th>
            </tr>
        {{-- </thead> --}}
        <tbody>
            @foreach ($data as $index => $item)
            @if ($item->id_ligne != null)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->date_fiche }}</td>
                <td>{{ $item->bus->name }}</td>
                <td>{{ $item->brigade }}</td>
                <td>{{ $item->chauffeur->fr_name }}</td>
                <td>{{ $item->ligne->name }}</td>
                <td>{{ $item->heur_depart }}</td>
                <td>{{ $item->heur_arrive }}</td>
                <td>{{ $item->gasoile }}</td>
                <td>{{ $item->kmdepart }}</td>
                <td>{{ $item->kmarrive }}</td>
                <td>{{ $item->kmgobale }}</td>
                <td>{{ $item->kmhlp }}</td>
                <td>{{ $item->kmcommerciale }}</td>
            </tr>
            @else
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->date_fiche }}</td>
                <td>{{ $item->bus->name  }}</td>
                <td>{{ $item->brigade }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
                <td>{{ "/" }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
