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
        }

        th,
        td {
            border: 1px solid #000;
            /* padding: 3px; */
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
            /* padding: 10px; */
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
            'taxis' => ' للحصول على دفتر النقل لسيارات الأجرة :',
            'tper' => '  للحصول على شهادة الكفاءة المهنية لنقل الأشخاص :',
            'tmar' => ' للحصول على شهادة الكفاءة المهنية لنقل البضائع :',
            'tdan' => ' للحصول على شهادة الكفاءة المهنية لنقل المواد الخطرة :',
            'mae' => ' شهادة الكفاءة المهنية و البيداغوجية لتعليم سياقة مركبات ذات محرك ',
        ];
    @endphp

    <div style="margin-bottom: 10px;font-weight:bold;"class="header">
        <p style="margin: -6px; font-size:17px;">الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p style="margin: -6px;font-size:17px;">وزارة النقل</p>
        <p style="margin: -6px;font-size:17px;">المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</p>
        <p style="margin: -6px;font-size:17px;">مركز التكوين</p>
    </div>

    <div style="margin-bottom: 10px;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:45px;"> شهادة تكوين</p>
    </div>
    <div style="position: absolute;top:250px;right:170px;font-size: 19px;font-weight:bold;margin: 0px;"class="header">
        <p style="font-size: 19px;"> رقم:{{ $list->type . '-' . $item->validation_number }}</p>
    </div>


    <table class="details-table" dir="rtl" style="width:100%;">
        <tr dir="rtl">
            <td style="font-size: 19px;text-align: center;width:100%;font-weight:bold;"> إن المدير العام للمؤسسة
                العمومية للنقل الحضري والشبه الحضري لولاية سيدي بلعباس</td>
        </tr>
        <tr>
            <td style="font-size: 11px;text-align: right; width:100%;padding-right:100px;"> بمقتضى المرسوم التنفيذي رقم
                09—168 المؤرخ في07 جمادى الأولى عام 1430 الموافق لـ 2 مايو سنة 2009 المتضمن
                إنشاء مؤسسة عمومية للنقل الحضري في مدينة سيدي بلعباس.</td>
        </tr>
        <tr>
            <td style="font-size: 11px;text-align: right;width:100%;padding-right:100px;"> بمقتضى المرسوم التنفيذي رقم
                10—91 المؤرخ في 28 ربيع الأول 1431 الموافق لـ 14 مارس 2010 المحدد للقانون
                الأساسي النموذجي للمؤسسة العمومية للنقل الحضري والشبه الحضري.</td>
        </tr>
        <tr>
            <td style="font-size: 11px;text-align: right;width:100%;padding-right:100px;"> بمقتضى المرسوم التنفيذي رقم
                12—230 المؤرخ في 03 رجب 1433 الموافق لـ 24 مايو 2012 المتضمن تنظيم النقل
                بواسطة سيارات الأجرة ، المعدل والمتمم.</td>
        </tr>
        <tr>
            <td style="font-size: 11px;text-align: right;width:100%;padding-right:100px;"> بمقتضى القرار المؤرخ في 11 ذو
                القعدة عام 1437 الموافق لـ 14 أوت 2016 ، يتضمن دفتر الشروط المتعلق بشروط
                وكيفيات استغلال خدمة سيارات الأجرة.</td>
        </tr>
        <tr>
            <td style="font-size: 11px;text-align: right;width:100%;padding-right:100px;"> بمقتضى المرسوم التنفيذي رقم
                21—367 المؤرخ في 20 صفر 1443 الموافق لـ 27 سبتمبر 2021 المتضمن تنظيم
                الإدارة المركزية لوزارة النقل.</td>
        </tr>
        <tr>
            <td style="font-size: 11px;text-align: right;width:100%;padding-right:100px;"> بمقتضى القرار الوزاري المؤرخ
                في 11 ذي القعدة عام 1437 الموافق 14 غشت سنة 2016 المحدد لشروط وكيفيات
                تسليم دفتر المقاعد للنقل بواسطة سيارة الأجرة.</td>
        </tr>
        <tr>
            <td style="font-size: 11px;text-align: right;width:100%;padding-right:100px;"> بمقتضى الإتفاقية المبرمة بين
                وزارة النقل والمؤسسة العمومية للنقل الحضري والشبه الحضري لولاية سيدي
                بلعباس في : 24 أكتوبر 2023.</td>
        </tr>
        <tr dir="rtl">
            <td style="font-size: 26px;text-align: center;width:100%;font-weight:bold;"> يشهد</td>
        </tr>
        <tr>
            <td style="font-size: 14px;text-align: right;width:100%;padding-right:100px;"><strong>أن السيد :</strong>
                {{ $item->nom_ar . ' ' . $item->prenom_ar }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;text-align: right;width:100%;padding-right:100px;"><strong>المولود بتاريخ
                    :</strong> {{ $item->birthdate }} بـ:{{ $item->birthplace }}</td>
        </tr>
        <tr>
            <td style="font-size: 14px;text-align: right;width:100%;padding-right:100px;"><strong>تابع تكوينا
                    {{ $types[$list->type] }} من:{{ $list->date_debut }} إلى: {{ $list->date_fin }}</strong></td>
        </tr>
        <tr>
            <td style="font-size: 19px;text-align: center;width:100%;font-weight:bold;"><strong>وقد إجتاز بنجاح امتحان
                    التقييم النهائي.</td>
        </tr>
        <tr>
            <td style="font-size: 16px; text-align: right; width:100%; padding-right:100px;padding-top:20px;">
                سيدي بلعباس
                يوم:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>المدير
                    العام</strong>
            </td>
        </tr>
        <tr>
            <td style="font-size: 16px; text-align: right; width:100%; padding-right:100px;padding-top:30px;font-weight:bold;">
                ملاحظة : لا تسلم إلا نسخة واحدة من هذه الشهادة
            </td>
        </tr>

    </table>
</body>
{{-- @php
    dd("hemm")
@endphp --}}

</html>
