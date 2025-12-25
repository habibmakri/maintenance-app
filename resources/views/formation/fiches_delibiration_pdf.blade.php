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
            'taxis' => ' للحصول على دفتر النقل لسيارات الأجرة ',
            'tper' => '  للحصول على شهادة الكفاءة المهنية لنقل الأشخاص ',
            'tmar' => ' للحصول على شهادة الكفاءة المهنية لنقل البضائع ',
            'tdan' => ' للحصول على شهادة الكفاءة المهنية لنقل المواد الخطرة ',
            'mae' => ' شهادة الكفاءة المهنية و البيداغوجية لتعليم سياقة مركبات ذات محرك ',
        ];
    @endphp

    <div style="margin-bottom: 5%;margin-left: 20%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:18px;">الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p style="margin: 0px;font-size:18px;">وزارة النقل</p>
        <p style="margin: 0px;font-size:18px;">المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</p>
        <p style="margin: 0px;font-size:18px;">مركز التكوين</p>
    </div>

    <div style="margin-bottom: 5%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:18px;"> إستمارة التقييم البيداغوجي
            {{ $types[$list->type] }}</p>
    </div>


    <table class="details-table" dir="rtl" style="font-size: 16px; text-align: right;">
        <tr>
            <td style="text-align: right;"><strong>اللقب :</strong> {{ $item->nom_ar }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>الاسم :</strong> {{ $item->prenom_ar }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><strong>الدورة :</strong> {{ $list->counter }}</td>
            <td style="text-align: right;"><strong>فوج :</strong> {{ $group }}</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 15px;"><strong>تاريخ بداية الدورة :</strong> {{ $list->date_debut }}</td>
            <td style="text-align: right;font-size: 15px;"><strong>تاريخ نهاية الدورة :</strong> {{ $list->date_fin }}</td>
            <td style="text-align: right;font-size: 15px;"><strong>تاريخ المداولات :</strong> {{ $list->valid_date }}</td>
        </tr>

    </table>
    @php
        $profs = json_decode($list->profs, true);
        $notes = json_decode($item->notes, true);
    @endphp
    <table border="1" style="border-collapse: collapse; width:100%; text-align:center;">
        <thead>
            <tr>
                <th>الرقم</th>
                <th>المواد</th>
                <th>نقطة الامتحان</th>
                <th>نقطة المواظبة</th>
                <th>أستاذ المادة</th>
                <th>إمضاء الأستاذ</th>
                <th>النتيجة</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $total = 0;
                $count = count($notes);
            @endphp

            @foreach ($notes as $matiere => $details)
                @php
                    $exam = $details['إمتحان'] ?? 0;
                    $moazaba = $details['مواضبة'] ?? 0;
                    $prof = $profs[$matiere] ?? '';
                    $moyenne = ($exam + $moazaba) / 2;
                    $total += $moyenne;
                    @endphp
            @endforeach
            @php
                $moyenneGenerale = $total / $count;
                $resultatFinal = $moyenneGenerale >= 8 ? 'ناجح' : 'راسب';
            @endphp
            @foreach ($notes as $matiere => $details)
                @php
                    $exam = $details['إمتحان'] ?? 0;
                    $presence = $details['مواضبة'] ?? 0;
                    $prof = $profs[$matiere] ?? '';
                    $moyenne = ($exam + $presence) / 2;
                @endphp

                <tr>
                    <td style="height: 60px;">{{ sprintf('%02d', $i++) }}</td>
                    <td style="height: 60px;">{{ $matiere }}</td>
                    <td style="height: 60px;">{{ $exam }}</td>
                    <td style="height: 60px;">{{ $presence }}</td>
                    <td style="height: 60px;">{{ $prof }}</td>
                    <td style="height: 60px;"></td>

                    {{-- Ajouter la cellule fusionnée du résultat uniquement à la première ligne --}}
                    @if ($loop->first)
                        
                        <td rowspan="{{ $count }}"
                            style="writing-mode: vertical-rl; text-orientation: mixed; vertical-align: middle; font-weight: bold; font-size: 20px;">
                            {{$resultatFinal}}
                        </td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="2"><strong>المعدل</strong></td>
                <td colspan="4">{{ number_format($moyenneGenerale, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <p style="font-size: 18px;font-weight:bold;text-decoration:underline;margin-top:45px;margin-right:435px;">المسؤول البيداغوجي</p>
</body>

</html>
