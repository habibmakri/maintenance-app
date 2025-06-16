<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Sakkal Majalla', sans-serif;
            font-size: 18px;
            line-height: 1.4;
            direction: rtl;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 20px;
            border-bottom: 2px solid black;
        }

        .header2 {
            text-align: center;
            margin-bottom: 16px;
            line-height: 1;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
        }

        .section-title {
            margin-top: 30px;
            font-weight: bold;
            text-decoration: underline;
        }

        .info-line {
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 60px;
            text-align: left;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="header2">
        <p>الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p>وزارة النقل</p>
        <p>مديرية النقل لولاية سيدي بلعباس</p>
        <p> المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس </p>
    </div>
    <div class="header">
        <h1>وصل تسجيل</h1>
        @if ($type_insc == 'tper')
            <h2> للحصول على شهادة الكفاءة المهنية
                لسائقي مركبات نقل الأشخاص</h2>
        @endif
        @if ($type_insc == 'tmar')
            <h2> للحصول على شهادة الكفاءة المهنية
                لسائقي مركبات نقل البضائع</h2>
        @endif
        @if ($type_insc == 'tdan')
            <h2> للحصول على شهادة الكفاءة المهنية
                لسائقي مركبات نقل المواد الخطرة</h2>
        @endif
        <p>رقم تسجيل:
            {{$type_insc}}_{{ $taxi->id }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            وقت التسجيل: <strong>{{ \Carbon\Carbon::parse($taxi->inscription_time)->format('H:i:s d-m-Y') }}</strong>
        </p>

    </div>

    @php
        $civilite = $taxi->gender === 'homme' ? 'السيد' : 'السيدة';
        $civilite2 = $taxi->gender === 'homme' ? 'هو يتحمل' : 'هي تتحمل';
        $taMarbota = $taxi->gender === 'homme' ? '' : 'ة';
        $taMaftoha = $taxi->gender === 'homme' ? '' : 'ت';
    @endphp

    <div class="info-line">
        {{ $civilite }}: <strong>{{ $taxi->nom_ar }} {{ $taxi->prenom_ar }}</strong>
        المولود{{ $taMarbota }} بتاريخ:
        <strong>{{ \Carbon\Carbon::parse($taxi->birthdate)->format('Y-m-d') }}</strong>
        بـ <strong>{{ $taxi->birthplace }}</strong>
    </div>

    <div class="info-line">
        والحامل{{ $taMarbota }} للرقم الوطني: <strong>{{ $taxi->nin }}</strong>، قد قام{{ $taMaftoha }}
        بالتسجيل في التكوين المتعلق بالحصول على
        دفتر المقاعد.
    </div>

    <div class="info-line">
        رقم الهاتف: <strong>{{ $taxi->phone }}</strong> – العنوان: <strong>{{ $taxi->adresse }}</strong>
    </div>
    @if ($taxi->email)
        <div class="info-line">
            البريد الإلكتروني: <strong>{{ $taxi->email }}</strong>
        </div>
    @endif

    <div class="info-line">
        رقم رخصة السياقة: <strong>{{ $taxi->n_permis }}</strong>، الصادرة بتاريخ:
        <strong>{{ \Carbon\Carbon::parse($taxi->date_permis)->format('Y-m-d') }}</strong>
        بـ <strong>{{ $taxi->lieu_permis }}</strong>
    </div>
    <div class="info-line">
        صنف رخضة السياقة: <strong>{{ $taxi->type_permis }}</strong>
    </div>

    {{-- <div class="info-line">
        بلدية الإستغلال: <strong>{{ $taxi->comune_exploi }}</strong>
    </div> --}}
    <div class="info-line">
        المعلومات المذكورة أعلاه مصرحة من طرف {{ $civilite }} و {{ $civilite2 }} مسؤوليتها. </div>

    {{-- <div class="info-line section-title">
        الملف المطلوب:
    </div> --}}

    {{-- <ul
        style="padding-right: 40px; list-style-type: square; font-family: 'Sakkal Majalla', sans-serif; font-size: 18px;">
        <li>نسخة من بطاقة التعريف الوطني.</li>
        <li>نسخة من رخصة السياقة.</li>
        <li>مستخرج من صفيحة السوابق العدلية.</li>
        <li>شهادة الإقامة.</li>
        <li>3 صور شمسية حديثة.</li>
        <li>ثلاث شهادات طبية تثبت اللياقة البدنية والعقلية وحدة البصر.</li>
        <li>شهادة عدم الانتساب إلى الضمان الإجتماعي.</li>
    </ul> --}}
    <div class="info-line section-title">
        ملاحظة:
    </div>
    <div class="info-line">
        سيتم استخراج وصل دفع مستحقات مع هذا الوصل يرجى إحضاره مع المبلغ مرفقا بالوثائق التالية:
    </div>
    <ul
        style="padding-right: 40px; list-style-type: square; font-family: 'Sakkal Majalla', sans-serif; font-size: 18px;">
        {{-- <li>نسخة من بطاقة التعريف الوطني.</li> --}}
        <li>ثلاث صور شمسية حديثة.</li>
        <li>نسخة من رخصة السياقة.</li>
        <li>شهادات طبية (طب عام،طب العيون،طب الأمراض الصدرية).
        </li>
        <li>تصريح إقامة ساري المفعول(أثناء مدة التكوين).</li>
        @if ($type_insc == 'tdan')
        <li>نسخة من شهادة الكفاءة المهنية لنقل البضائع.</li>
        @endif
    </ul>
    
</body>

</html>
