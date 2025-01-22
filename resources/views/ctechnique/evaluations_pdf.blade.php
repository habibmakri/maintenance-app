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
        <h2> الفترة من {{ $du }} إلى {{ $au }}</h2>
    </div>
    <table style="font-size: 14px;">
            <tr>
                {{-- <th>#</th> --}}
                <th>التاريخ</th>
                <th>الخدمة</th>
                <th>المراقب</th>
                <th>النظافة</th>
                <th>النظام</th>
                <th>رسالة</th>
                <th>رقم الهاتف</th>
            </tr>
        <tbody>
            @foreach ($ratings as $rating)
            <tr>
                <td>{{ $rating->created_at }}</td>
                <td>{{ $rating->service }}</td>
                <td>{{ $rating->controler }}</td>
                <td>{{ $rating->clean }}</td>
                <td>{{ $rating->order }}</td>
                <td>{{ $rating->message }}</td>
                <td>{{ $rating->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
