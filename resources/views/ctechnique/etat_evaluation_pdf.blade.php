<!DOCTYPE html>
<html lang="ar">
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

        th,
        td {
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
<body dir="rtl">
    <div style="text-align: center;">
        <h1 style="margin-left: 15%;">تقييامات تطبيق قيمني</h1>
        <h2 style="margin-left: 15%;"> الفترة من {{ $du }} إلى {{ $au }}</h2>
    </div>
    <table style="font-size: 14px;">
            <tr>
                {{-- <th>#</th> --}}
                <th> / </th>
                <th>الخدمة</th>
                <th>المراقب</th>
                <th>النظافة</th>
                <th>النظام</th>
                <th>المجموع</th>
            </tr>
        <tbody>
            <tr>
                <td>جيدة</td>
                <td>{{ $sbien }}</td>
                <td>{{ $cbien }}</td>
                <td>{{ $clbien }}</td>
                <td>{{ $obien }}</td>
                <td>{{ $sbien+$cbien+$clbien+$obien }}</td>
            </tr>
            <tr>
                <td>متوسطة</td>
                <td>{{ $smoyen }}</td>
                <td>{{ $cmoyen }}</td>
                <td>{{ $clmoyen }}</td>
                <td>{{ $omoyen }}</td>
                <td>{{ $smoyen+$cmoyen+$clmoyen+$omoyen }}</td>
            </tr>
            <tr>
                <td>سيئة</td>
                <td>{{ $smauvais }}</td>
                <td>{{ $cmauvais }}</td>
                <td>{{ $clmauvais }}</td>
                <td>{{ $omauvais }}</td>
                <td>{{ $smauvais+$cmauvais+$clmauvais+$omauvais }}</td>
            </tr>
            <tr>
                <td>المجموع</td>
                <td>{{ $sbien + $smoyen + $smauvais }}</td>
                <td>{{ $cbien + $cmoyen + $cmauvais }}</td>
                <td>{{ $clbien + $clmoyen + $clmauvais }}</td>
                <td>{{ $obien + $omoyen + $omauvais }}</td>
                <td>{{ $sbien+$cbien+$clbien+$obien+$smoyen+$cmoyen+$clmoyen+$omoyen+$smauvais+$cmauvais+$clmauvais+$omauvais }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
