<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
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
        <h1 style="margin-left: 15%;">NOMBRE DE RÉPARATION MENSUELLE POUR LE MOIS DE <br>{{$monthName}} {{$year}}</h1>
    </div>
    <table style="font-size: 14px;">
            <tr>
                {{-- <th>#</th> --}}
                <th>Bus</th>
                <th>Mécanique</th>
                <th>Eléctrique</th>
                <th>Tole</th>
                <th>Total</th>
            </tr>
        <tbody>
            @foreach ($data as $item)
            <tr>
                {{-- {{dd($data)}} --}}
                <td>{{ $item['bus'] }}</td>
                <td>{{ $item['mecanique'] }}</td>
                <td>{{ $item['electrique'] }}</td>
                <td>{{ $item['tole'] }}</td>
                <td>{{ $item['mecanique']+$item['electrique']+$item['tole'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
