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
    </div>


    <table class="details-table" dir="rtl" style="font-size: 16px; text-align: right;">
        <tr>
            <td><strong>تاريخ البداية :</strong> {{ $list->date_debut }}</td>
            <td><strong>تاريخ النهاية :</strong> {{ $list->date_fin }}</td>
            @php
                $type = $list->type;
                $members = $list->count_models($type)->get();
                $count = $members->count();
            @endphp
            <td><strong>عدد المنخرطين :</strong> {{ $count ?? [] }}</td>
        </tr>
        @php
            $profs = json_decode($list->profs, true);
        @endphp
        @if ($profs)
            <tr>
                <td colspan="3" dir="rtl" style="font-size: 16px; text-align: right;"><strong>الأساتذة
                        :</strong></td>
            </tr>
            @foreach ($profs as $module => $prof)
                <tr>
                    <td colspan="3" dir="rtl" style="font-size: 16px; text-align: right;"> {{ $prof }} :
                        {{ $module }}</td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td colspan="3" dir="rtl" style="font-size: 16px; text-align: right;"><strong>المنخرطين :</strong>
            </td>
        </tr>
    </table>

    <table style="font-size:14px;">
        <thead>
            <tr>
                <td style="font-weight: bold;width:5%">الرقم</td>
                <td style="font-weight: bold;width:15%">اللقب</td>
                <td style="font-weight: bold;width:15%">الاسم</td>
                <td style="font-weight: bold;width:20%">تاريخ ومكان الميلاد</td>
                <td style="font-weight: bold;width:25%">المعدل العام</td>
                <td style="font-weight: bold;width:20%">القرار</td>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($list->count_models($list->type)->get() as $taxi)
                @php
                    $notes = json_decode($taxi->notes, true) ?? [];

                    $total = 0;
                    $count = 0;

                    foreach ($notes as $moduleNotes) {
                        if (isset($moduleNotes['مواضبة'], $moduleNotes['إمتحان'])) {
                            $note1 = floatval($moduleNotes['مواضبة']);
                            $note2 = floatval($moduleNotes['إمتحان']);

                            $moyModule = ($note1 + $note2) / 2;
                            $total += $moyModule;
                            $count++;
                        }
                    }

                    $moyenneGenerale = $count > 0 ? round($total / $count, 2) : 0;
                    $decision = $moyenneGenerale >= 8 ? 'ناجح' : 'راسب';
                @endphp
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $taxi->nom_ar }}</td>
                    <td>{{ $taxi->prenom_ar }}</td>
                    <td>{{ $taxi->birthdate }}<br>{{ $taxi->birthplace }}</td>
                    <td>{{ $moyenneGenerale }}</td>
                    <td>{{ $decision }}</td>
                </tr>
                @php
                    $i = $i + 1;
                @endphp
            @endforeach
        </tbody>
    </table>

</body>

</html>
