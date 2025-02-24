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
        th, td {
            border: 1px solid #000;
            padding: 4px;
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
        <p style="margin: 0px; font-size:12px;">REPUBLIQUE ALGERIENNE DEMOCRATIQUE ET POPULAIRE</p>
        <p style="margin: 0px;font-size:12px;">MINISTERE DES TRANSPORTS</p>
        <p style="margin: 0px;font-size:12px;">ETABLISSEMENT PUBLIC DE TRANSPORT URBAIN ET SUBURBAIN</p>
        <p style="margin: 0px;font-size:12px;">ETUS- SIDI BEL ABBES</p>
        <p style="margin: 0px;font-size:12px;">SERVICE DE LA MAINTENANCE</p>
        <h1>Etat de Vidange {{$typevidange}}</h1>
    </div>
    <table style="font-size: 14px;">
            <tr>
                {{-- <th>#</th> --}}
                <th>Bus</th>
                <th>Kilométrage</th>
                <th>Dernier vidange {{$typevidange}}</th>
                <th>Reste</th>
            </tr>
        <tbody>
            @foreach ($buses as $bus)
            <tr>
                {{-- <td>{{ $index + 1 }}</td> --}}
                <td>{{ $bus->name }}</td>
                <td>{{ $bus->kmactuelle }}</td>
                <td>@if($typevidange =='moteur'){{$bus->derniervidange}}@elseif($typevidange =='boite'){{$bus->$bus->derniervidangeboite}}@elseif ($typevidange =='pond'){{$bus->$bus->derniervidangepond}}@endif</td>
                <td>@if($typevidange =='moteur'){{$bus->kmactuelle-$bus->derniervidange}}@elseif($typevidange =='boite'){{$bus->kmactuelle-$bus->derniervidangeboite}}@elseif ($typevidange =='pond'){{$bus->kmactuelle-$bus->derniervidangepond}}@endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
