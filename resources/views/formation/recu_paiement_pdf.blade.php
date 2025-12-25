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

    <div style="margin-bottom: 15%;margin-left: 20%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:18px;">الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p style="margin: 0px;font-size:18px;">وزارة النقل</p>
        <p style="margin: 0px;font-size:18px;">المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</p>
        <p style="margin: 0px;font-size:18px;">مركز التكوين</p>
    </div>

    <div style="margin-bottom: 15%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:28px;"> وصل دفع المصاريف رقم {{ $item->payment_number }} بتاريخ
            {{ \Carbon\Carbon::parse($item->date_paiement)->format('d-m-Y') }}
</p>
    </div>

    <table class="details-table" dir="rtl" style="font-size: 18px; ">
        <tr>
            <td style="padding:10px; line-height:1.8; text-align: justify;">
                <p> تم دفع مصاريف التكوين و المقدرة بـ {{ $item->montant_paiement }} دج من طرف السيد:
                    {{ $item->nom_ar }} {{ $item->prenom_ar }} للتسجيل بمركز تكوين المؤسسة العمومية للنقل الحضري و شبه
                    الحضري سيدي بلعباس للحصول على {{ $types[$type_insc] }} بناءا على وصل البنك رقم:
                    {{ $item->cheque_number }} المؤرخ في: {{ \Carbon\Carbon::parse($item->date_paiement)->format('d-m-Y') }}.</p>
            </td>
        </tr>
        <tr>
            <td style="padding-top:30px; line-height:1.8; text-align: justify;"><strong>ملاحظة هامة: </strong> على
                المترشح تلبية الاستدعاء الموجه إليه لمباشرة عملية التكوين بمركز تكوين المؤسسة وفق البرنامج المسطر.</td>
        </tr>
    </table>
    <table class="details-table" dir="rtl" style="font-size: 18px; ">
        <tr>
            <td style="padding-top:130px;padding-right:50px; width:50%; text-align: right;"><strong>مسؤول التكوين</strong></td>
            <td style="padding-top:130px;padding-left:100px; width:50%;text-align: left"><strong>المترشح</strong></td>
        </tr>
    </table>

</body>

</html>
