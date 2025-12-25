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
            'Moniteur Auto Ecole' => ' شهادة الكفاءة المهنية و البيداغوجية لتعليم سياقة مركبات ذات محرك ',
        ];
    @endphp

    <div style="margin-bottom: 9%;margin-left: 20%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:18px;">الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p style="margin: 0px;font-size:18px;">وزارة النقل</p>
        <p style="margin: 0px;font-size:18px;">المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</p>
        <p style="margin: 0px;font-size:18px;">مركز التكوين</p>
    </div>

    <div style="margin-bottom: 10%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:36px;"> استمارة التسجيل</p>
    </div>
    <div style="position: absolute;top:270px;right:40px;font-size: 16px;font-weight:bold;margin: 0px;"class="header">
        <p style="font-size: 19px;"> رقم المترشح:{{ $item->validation_number }}</p>
    </div>
    <div
        style="position: absolute;top:310px;right:590px;font-size: 16px;font-weight:bold;margin: 0px;border: solid;padding: 60px 30px;"class="header">
        <p style="font-size: 19px;"> الصورة</p>
    </div>


    <table class="details-table" dir="rtl" style="font-size: 18px; text-align: right;text-wrap-mode: nowrap;">
        <tr>
            <td style="text-align: right;"><strong>اللقب :</strong> {{ $item->nom_ar }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>الاسم :</strong> {{ $item->prenom_ar }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>العنوان :</strong> {{ $item->adresse }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>الهاتف :</strong> {{ $item->phone }}</td>
            {{-- @if ($item->email)
                <td style="text-align: right;"><strong>البريد الإلكتروني :</strong> {{ $item->email }}</td>
            @endif --}}
        </tr>
        <tr>
            <td style="text-align: right;"><strong>تاريخ التسجيل :</strong>
                {{ \Carbon\Carbon::parse($item->date_paiement)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>التكوين المرغوب فيه :</strong>
                {{ $types[$type_insc] }}</td>
        </tr>
        <tr>
    </table>
    <table class="details-table" dir="rtl" style="font-size: 18px; text-align: right;text-wrap-mode: nowrap;">

        <tr>
            <td style="text-align: right;"><strong>ملف التسجيل :</strong></td>
        </tr>
        <tr>
            <td style="text-align: right;">1-ثلاث صور شمسية حديثة.</td>
        </tr>
        <tr>
            <td style="text-align: right;">2-نسخة من رخصة السياقة.</td>
        </tr>
        <tr>
            <td style="text-align: right;width: 300px;padding-bottom:70px;">3-تصريح إقامة ساري المفعول.</td>
        </tr>
        <tr>
            <td style="text-align: center; padding:5px; font-size: 12px; width:33%;border: solid;border-width:1px;">مبلغ
                المصاريف:
                {{ $item->montant_paiement }}</td>
            <td style="text-align: center; padding:5px; font-size: 12px; width:33%;border: solid;border-width:1px;">رقم
                وصل البنك:
                {{ $item->cheque_number }}</td>
            <td style="text-align: center; padding:5px; font-size: 12px; width:33%;border: solid;border-width:1px;">
                تاريخ وصل
                البنك:{{ \Carbon\Carbon::parse($item->date_paiement)->format('d-m-Y') }}</td>
        </tr>
    </table>

    <p style="font-size: 18px;font-weight:bold;text-decoration:underline;margin-top:45px;margin-right:200px;">ملف مدروس
        من طرف:</p>
</body>
{{-- @php
    dd("hello")
@endphp --}}

</html>
