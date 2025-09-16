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
            'Carnet Taxi' => ' دفتر النقل لسيارات الأجرة ',
            'Tansport personne' => '  شهادة الكفاءة المهنية لنقل الأشخاص ',
            'Tansport Marchendise' => ' شهادة الكفاءة المهنية لنقل البضائع ',
            'Tansport Materieux Dangereux' => ' شهادة الكفاءة المهنية لنقل المواد الخطرة ',
        ];
    @endphp

    <div style="margin-bottom: 9%;margin-left: 20%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:18px;">الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p style="margin: 0px;font-size:18px;">وزارة النقل</p>
        <p style="margin: 0px;font-size:18px;">المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</p>
        <p style="margin: 0px;font-size:18px;">مركز التكوين</p>
    </div>

    <div style="margin-bottom: 10%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:36px;"> طلب فاتورة شكلية</p>
    </div>


    <table class="details-table" dir="rtl" style="font-size: 18px; text-align: right;text-wrap-mode: nowrap;">
        <tr>
            <td style="text-align: center;font-size: 22px;"><strong>معلومات المؤسسة </strong></td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>مؤسسة :</strong> {{ $item->name }}</td>
            <td style="text-align: right;"><strong>المسير :</strong> {{ $item->gerant }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>النشاط :</strong> {{ $item->activity }}</td>
            <td style="text-align: right;"><strong>العنوان :</strong> {{ $item->adresse }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>الهاتف :</strong> {{ $item->phone }}</td>
            <td style="text-align: right;"><strong>البريد الإلكتروني :</strong> {{ $item->email }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>رقم السجل التجاري :</strong> {{ $item->nrc }}</td>
            <td style="text-align: right;"><strong>رقم التعريف الجبائي NIF :</strong> {{ $item->nif }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>رقم التعريف الإحصائي NIS :</strong> {{ $item->nis }}</td>
        </tr>
        <tr>
            <td style="text-align: center;font-size: 22px;"><strong>العمال والتكوين المرغوب فيه</strong></td>
        </tr>
    </table>
    <table>
        <tr>
            <th>العامل</th>
            <th>التكوين المرغوب فيه</th>
        </tr>
        @foreach ($item->count_tper_emps as $emp)
            <tr>
                <td style="text-align: center;"><strong>{{ $emp->nom_ar }} {{ $emp->prenom_ar }}</strong></td>
                <td style="text-align: center;"><strong>نقل الأشخاص</strong></td>
            </tr>
        @endforeach
        @foreach ($item->count_tmar_emps as $emp)
            <tr>
                <td style="text-align: center;"><strong>{{ $emp->nom_ar }} {{ $emp->prenom_ar }}</strong></td>
                <td style="text-align: center;"><strong>نقل البضائع</strong></td>
            </tr>
        @endforeach
        @foreach ($item->count_tdan_emps as $emp)
            <tr>
                <td style="text-align: center;"><strong>{{ $emp->nom_ar }} {{ $emp->prenom_ar }}</strong></td>
                <td style="text-align: center;"><strong>نقل المواد الخطرة</strong></td>
            </tr>
        @endforeach
    </table>

</body>

</html>
