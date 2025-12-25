<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Sakkal Majalla', sans-serif;
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

<body dir="rtl">
    @php
        $types = [
            'taxis' => 'التكوين للحصول على دفتر النقل لسيارات الأجرة ',
            'tper' => ' التكوين للحصول على شهادة الكفاءة المهنية لنقل الأشخاص ',
            'tmar' => 'التكوين للحصول على شهادة الكفاءة المهنية لنقل البضائع ',
            'tdan' => 'التكوين للحصول على شهادة الكفاءة المهنية لنقل المواد الخطرة ',
            'mae' => ' شهادة الكفاءة المهنية و البيداغوجية لتعليم سياقة مركبات ذات محرك ',
        ];
    @endphp

    <div style="margin-bottom: 7%;margin-left: 20%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:18px;">الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p style="margin: 0px;font-size:18px;">وزارة النقل</p>
        <p style="margin: 0px;font-size:18px;">المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</p>
        <p style="margin: 0px;font-size:18px;">مركز التكوين</p>
    </div>

    <div style="margin-bottom: 7%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:18px;">الدورة رقم {{ $list->counter }}
            {{ $types[$list->type] }}</p>
            <p style="margin: 0px;font-size:18px;">ورقة الحضور: الفوج{{$group}}</p>
    </div>
<table class="details-table" dir="rtl" style="font-size: 16px; text-align: right;">
        <tr>
            <td style="text-align: right;"><strong>اليوم :</strong></td>
            <td style="text-align: right;"><strong>المادة:</strong></td>
            <td style="text-align: right;"><strong>الأستاذ :</strong></td>
        </tr>
    </table>
    <table style="font-size:14px;">
        <thead>
            <tr>
                <td style="font-weight: bold;width:5%">الرقم</td>
                <td style="font-weight: bold;width:15%">اللقب</td>
                <td style="font-weight: bold;width:15%">الاسم</td>
                <td style="font-weight: bold;width:20%">تاريخ ومكان الميلاد</td>
                <td style="font-weight: bold;width:25%">الإمضاء</td>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($ls as $taxi)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $taxi->nom_ar }}</td>
                    <td>{{ $taxi->prenom_ar }}</td>
                    <td>{{ $taxi->birthdate }}<br>{{ $taxi->birthplace }}</td>
                    <td> </td>

                    @php
                        $i = $i + 1;
                    @endphp
            @endforeach
        </tbody>
    </table>

</body>

</html>
