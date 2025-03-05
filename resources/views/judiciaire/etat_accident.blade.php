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
        <p style="margin: 0px;font-size:28px;">حالة الحوادث</p>
        <h2 style="margin: 0px;font-size:28px;">{{ $monthName }}</h2>
    </div>

    <table style="font-size:10px;">
        <thead>
            <tr>
                <td style="font-weight: bold">الرقم</td>
                <td style="font-weight: bold">التاريخ</td>
                <td style="font-weight: bold">السائق</td>
                <td style="font-weight: bold">الحافلة</td>
                <td style="font-weight: bold">المكان</td>
                <td style="font-weight: bold">الوقت</td>
                <td style="font-weight: bold">التصريح لدى التأمينات</td>
                <td style="font-weight: bold">التعويض المتلقى</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($declarations as $declaration)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($declaration->date_fiche)->format('Y') .'/'.$declaration->number }}</td>
                    <td>{{ \Carbon\Carbon::parse($declaration->time_day)->format('Y/m/d') }}</td>
                    <td>{{ $declaration->chauffeur->name }}</td>
                    <td>{{ $declaration->bus->name }}</td>
                    <td>{{ $declaration->place }}</td>
                    <td>{{ \Carbon\Carbon::parse($declaration->date_paye)->format('H:i') }}</td>
                    <td> @if($declaration->caat)صرح يوم {{$declaration->date_caat}} @else لم يتم التصريح @endif</td>
                    <td> @if($declaration->paye){{$declaration->paye_montant}} دج يوم{{\Carbon\Carbon::parse($declaration->date_paye)->format('Y/m/d')}}@else لم يتم تلقي تعويض @endif</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>

</html>
