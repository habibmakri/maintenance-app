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
        <h1>Etat de consomation de gasoile au 100 KM</h1>
        @if ($datedupdf == $dateaupdf)
        <h2 >Le {{ $datedupdf }} </h2>
        @else
        <h2 >Période du {{ $datedupdf }} au {{ $dateaupdf }} |</h2>
        @endif
    </div>
    <table style="font-size: 14px;">
            <tr>
                {{-- <th>#</th> --}}
                <th>Bus</th>
                <th>Kilométrage</th>
                <th>Gasoile</th>
                <th>Cons/100</th>
            </tr>
        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                {{-- <td>{{ $index + 1 }}</td> --}}
                <td>{{ $item->bus_name }}</td>
                <td>{{ $item->total_km }}</td>
                <td>{{ $item->total_gasoile }}</td>
                <td>@if($item->total_km>0){{number_format(($item->total_gasoile * 100) / $item->total_km, 2) }}@else 0 @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
