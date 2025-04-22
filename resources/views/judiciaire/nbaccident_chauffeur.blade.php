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
        <p style="margin: 0px;font-size:28px;">عدد الحوادث حسب السائق</p>
        <h2 style="margin: 0px;font-size:28px;">{{ $monthName }}</h2>
    </div>
    
    <table style="font-size:18px;">
        <thead>
            <tr>
                <td style="font-weight: bold; width: 50px;">الرقم</td>
                <td style="font-weight: bold; width: 450px;">السائق</td>
                <td style="font-weight: bold; width: 120px;">عدد الحوادث</td>
                <td style="font-weight: bold; width: 120px;">من السائق</td>
                <td style="font-weight: bold; width: 120px;">ليس من السائق</td>
            </tr>
        </thead>
        <tbody>
            {{$i =1;}}
            @foreach ($declarations as $declaration)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $declaration->chauffeur->name }}</td>
                    <td>{{ $declaration->count_declarations }}</td>
                    <td>{{ $declaration->count_true }}</td>
                    <td>{{ $declaration->count_false }}</td>
                </tr>
                {{$i++}}
            @endforeach
        </tbody>
    </table>
    
</body>

</html>
