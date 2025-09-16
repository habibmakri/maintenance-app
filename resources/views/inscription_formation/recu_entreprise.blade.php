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
        <p> مركز التكوين </p>
    </div>
    <div class="header">
        <h1>وصل فتح حساب خاص بمؤسسة {{ $taxi->name }}</h1>
    </div>


    <div class="info-line" style="text-align: center;font-size:38px;margin-top:70px;">
        البريد الإلكتروني: <strong>{{ $taxi->email }}</strong>
        <br>
        كلمة المرور: <strong>{{ $password }}</strong>
    </div>


</body>

</html>
