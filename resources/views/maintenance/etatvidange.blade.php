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
        <h1>Etat de Vidange {{ $typevidange }}</h1>
    </div>
    <table style="font-size: 14px;">
        <tr>
            {{-- <th>#</th> --}}
            <th>Bus</th>
            <th>Kilométrage</th>
            <th>Dernier vidange {{ $typevidange }}</th>
            <th>Diffirance</th>
        </tr>
        <tbody>
            @foreach ($buses as $bus)
                @if ($bus->type == 'v8' || ($bus->type == 'l5'))
                    <tr>
                        {{-- <td>{{ $index + 1 }}</td> --}}
                        <td>{{ $bus->name }}</td>
                        <td>{{ $bus->kmactuelle }}</td>
                        <td>
                            @if ($typevidange == 'moteur')
                                {{ $bus->derniervidange ?? 0 }}
                            @elseif($typevidange == 'boite')
                                {{ $bus->derniervidangeboite ?? 0 }}
                            @elseif($typevidange == 'pond')
                                {{ $bus->derniervidangepond ?? 0 }}
                            @endif
                        </td>
                        @if ($bus->type == 'v8')
                            <td>
                                @if ($typevidange == 'moteur')
                                    {{ $bus->kmactuelle - $bus->derniervidange ?? 0 }}/ 8000
                                @elseif($typevidange == 'boite')
                                    {{ $bus->kmactuelle - $bus->derniervidangeboite ?? 0 }}/ 50000
                                @elseif($typevidange == 'pond')
                                    {{ $bus->kmactuelle - $bus->derniervidangepond ?? 0 }}/ 100000
                                @endif
                            </td>
                        @else
                            <td>
                                @if ($typevidange == 'moteur')
                                    {{ $bus->kmactuelle - $bus->derniervidange ?? 0 }}/ 8000
                                @elseif($typevidange == 'boite')
                                    {{ $bus->kmactuelle - $bus->derniervidangeboite ?? 0 }}/ 30000
                                @elseif($typevidange == 'pond')
                                    {{ $bus->kmactuelle - $bus->derniervidangepond ?? 0 }}/ 100000
                                @endif
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>

</html>
