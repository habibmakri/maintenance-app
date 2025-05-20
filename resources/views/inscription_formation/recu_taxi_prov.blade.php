<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Sakkal Majalla', sans-serif;
            font-size: 18px;
            line-height: 1.8;
            direction: rtl;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            margin-top: 30px;
        }

        .header2 {
            text-align: center;
            margin-bottom: 22px;
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
        <p>المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</p>
    </div>
    <div class="header">
        <h1>وصل تسجيل</h1>
        <h2>للتكوين للحصول على دفتر المقاعد لسائقي سيارات الأجرة - 2025</h2>
    </div>

    @php
        $civilite = $taxi->gender === 'homme' ? 'السيد' : 'السيدة';
    @endphp

    <div class="info-line">
        {{ $civilite }}: <strong>{{ $taxi->nom_ar }} {{ $taxi->prenom_ar }}</strong>
        المولود(ة) بتاريخ: <strong>{{ \Carbon\Carbon::parse($taxi->birthdate)->format('Y-m-d') }}</strong>
        بـ <strong>{{ $taxi->birthplace }}</strong>
    </div>

    <div class="info-line">
        والحامل(ة) للرقم الوطني: <strong>{{ $taxi->nin }}</strong>، قد قام(ت) بالتسجيل في التكوين المتعلق بالحصول على
        دفتر المقاعد.
    </div>

    <div class="info-line">
        رقم الهاتف: <strong>{{ $taxi->phone }}</strong> – العنوان: <strong>{{ $taxi->adresse }}</strong>
    </div>

    <div class="info-line">
        رقم رخصة السياقة: <strong>{{ $taxi->n_permis }}</strong>، الصادرة بتاريخ:
        <strong>{{ \Carbon\Carbon::parse($taxi->date_permis)->format('Y-m-d') }}</strong>
        بـ <strong>{{ $taxi->lieu_permis }}</strong>
    </div>

    @if ($taxi->email)
        <div class="info-line">
            البريد الإلكتروني: <strong>{{ $taxi->email }}</strong>
        </div>
    @endif

    <div class="info-line">
        بلدية الإستغلال: <strong>{{ $taxi->comune_exploi }}</strong>
    </div>

    <div class="info-line section-title">
        معلومات إضافية:
    </div>
    <div class="info-line">
        تاريخ التسجيل: <strong>{{ \Carbon\Carbon::parse($taxi->inscription_time)->format('H:i:s d-m-Y') }}</strong>
    </div>
    <div class="info-line">
المعلومات المذكورة أعلاه مصرحة من طرف {{$civilite}} و هو يتحمل مسؤوليتها.    </div>


</body>

</html>
