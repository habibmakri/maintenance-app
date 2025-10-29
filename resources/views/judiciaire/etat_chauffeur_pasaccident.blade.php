<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: arial, sans-serif;
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

<body dir="rtl" >
    <div style="margin-bottom: 7%;margin-left: 20%;"class="header">
        <p style="margin: 0px; font-size:18px;">الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p style="margin: 0px;font-size:18px;">وزارة النقل</p>
        <p style="margin: 0px;font-size:18px;">المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</p>
        <p style="margin: 0px;font-size:18px;">مكتب الشؤون القانونية والمنازعات</p>
        <p style="margin: 0px;font-size:24px;">حالة السائقين الذين لم يرتكبوا حوادث</p>
        @if ($year != 0)
        <h2 style="margin: 0px;font-size:24px;">لسنة {{ $year }}</h2>
        @endif
    </div>
    @php
        $i = 1;
    @endphp

    <table style="font-size:12px;">
        <thead>
            <tr>
                <td style="font-weight: bold">الرقم</td>
                <td style="font-weight: bold">الإسم واللقب</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($chauffeurs as $chauffeur)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $chauffeur->name }}</td>
                </tr>
                @php
                    $i = $i+1;
                @endphp
            @endforeach
        </tbody>
    </table>
    
</body>

</html>
