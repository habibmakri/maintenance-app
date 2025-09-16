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

    <div style="margin-bottom: 2%;font-weight:bold;"class="header">
        <p style="margin: 0px; font-size:18px;">نتائج مداولات الدورة رقم {{ $list->counter }}
            {{ $types[$list->type] }}</p>
    </div>


    @php
        $firstTaxi = $list->count_models($list->type)->first();
        $notes = json_decode($firstTaxi->notes ?? '{}', true);
        $modules = array_keys($notes ?? []);
    @endphp
    @php
        $students = $list->count_models($list->type)->get();
        $totalStudents = $students->count();
        $groupSize = ceil($totalStudents / $list->groups); // students per group
        $groupNumber = 1;
    @endphp

    @for ($g = 0; $g < $list->groups; $g++)
        <h1 style="margin-top:10px; font-size:22px; text-align:center;">
            فوج {{ $groupNumber }}
        </h1>

        <table style="font-size:14px; direction: rtl; text-align: center;" border="1" cellspacing="0"
            cellpadding="5">
            <thead>
                <tr>
                    <th rowspan="2" style="width:5%;">#</th>
                    <th rowspan="2" style="width:15%;">الاسم واللقب</th>

                    @foreach ($modules as $mod)
                        <th colspan="3">{{ $mod }}</th>
                    @endforeach

                    <th rowspan="2">المعدل العام</th>
                    <th rowspan="2">الملاحظة</th>
                </tr>
                <tr>
                    @foreach ($modules as $mod)
                        {{-- <th>مواضبة</th> --}}
                        <th>ن م</th>
                        {{-- <th>إمتحان</th> --}}
                        <th>ن إ</th>
                        {{-- <th>معدل المادة</th> --}}
                        <th>م م</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $start = $g * $groupSize;
                    $end = min(($g + 1) * $groupSize, $totalStudents);
                    $i = 1;
                @endphp

                @for ($s = $start; $s < $end; $s++)
                    @php
                        $taxi = $students[$s];
                        $notes = json_decode($taxi->notes ?? '{}', true);
                        $total = 0;
                        $count = 0;
                    @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $taxi->nom_ar }} {{ $taxi->prenom_ar }}</td>

                        @foreach ($modules as $mod)
                            @php
                                $note1 = isset($notes[$mod]['مواضبة']) ? floatval($notes[$mod]['مواضبة']) : '';
                                $note2 = isset($notes[$mod]['إمتحان']) ? floatval($notes[$mod]['إمتحان']) : '';
                                $moy = '';

                                if ($note1 !== '' && $note2 !== '') {
                                    $moy = ($note1 + $note2) / 2;
                                    $total += $moy;
                                    $count++;
                                }
                            @endphp
                            <td>{{ $note1 }}</td>
                            <td>{{ $note2 }}</td>
                            <td>{{ $moy }}</td>
                        @endforeach

                        @php
                            $moyenne = $count > 0 ? round($total / $count, 2) : 0;
                            $decision = $moyenne >= 8 ? 'ناجح' : 'راسب';
                        @endphp

                        <td>{{ $moyenne }}</td>
                        <td>{{ $decision }}</td>
                    </tr>
                    @php $i++; @endphp
                @endfor
            </tbody>
        </table>

        @php $groupNumber++; @endphp
    @endfor

    {{-- <table style="font-size:14px; direction: rtl; text-align: center;" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th rowspan="2" style="width:5%;">#</th>
                <th rowspan="2" style="width:15%;">الاسم واللقب</th>

                @foreach ($modules as $mod)
                    <th colspan="3">{{ $mod }}</th>
                @endforeach

                <th rowspan="2">المعدل العام</th>
                <th rowspan="2">القرار</th>
            </tr>
            <tr>
                @foreach ($modules as $mod)
                    <th>مواضبة</th>
                    <th>إمتحان</th>
                    <th>معدل المادة</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($list->count_models($list->type)->get() as $taxi)
                @php
                    $notes = json_decode($taxi->notes ?? '{}', true);
                    $total = 0;
                    $count = 0;
                @endphp
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $taxi->nom_ar }} {{ $taxi->prenom_ar }}</td>

                    @foreach ($modules as $mod)
                        @php
                            $note1 = isset($notes[$mod]['مواضبة']) ? floatval($notes[$mod]['مواضبة']) : '';
                            $note2 = isset($notes[$mod]['إمتحان']) ? floatval($notes[$mod]['إمتحان']) : '';

                            if ($note1 !== '' && $note2 !== '') {
                                $moy = ($note1 + $note2) / 2;
                                $total += $moy;
                                $count++;
                            }
                        @endphp
                        <td>{{ $note1 }}</td>
                        <td>{{ $note2 }}</td>
                        <td>{{ $moy }}</td>
                    @endforeach

                    @php
                        $moyenne = $count > 0 ? round($total / $count, 2) : 0;
                        $decision = $moyenne >= 8 ? 'ناجح' : 'راسب';
                    @endphp

                    <td>{{ $moyenne }}</td>
                    <td>{{ $decision }}</td>
                </tr>
                @php $i++; @endphp
            @endforeach
        </tbody>
    </table> --}}

    @php
        $profs = json_decode($list->profs, true);
        $names = array_values($profs);
    @endphp

    <br><br>
    <div style="direction: rtl; text-align: center; font-size: 16px; margin-top: 30px;">
        <table style="margin: 0 auto; border-collapse: collapse;">
            @foreach (array_chunk($names, 2) as $line)
                <tr style="height: 80px;">
                    @foreach ($line as $name)
                        <td style="padding: 10px; border: none;text-align:right;height: 80px;">
                            <div style="margin-bottom: 10px;">الأستاذ: {{ $name }}</div>
                            <div style="width: 200px; height: 40px;"></div>
                        </td>
                    @endforeach
                    @if (count($line) == 1)
                        <td style="padding: 10px; border: none;"></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>


</html>
