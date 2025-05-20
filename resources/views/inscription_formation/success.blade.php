<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Favicons -->
    {{-- <link href="assets/img/favicon.png" rel="icon"> --}}
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">



    <!-- JavaScript for Tom Select -->
    <link href="{{ asset('/theme/tomselect/tom-select.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/theme/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/boxicons/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.bubble.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/remixicon/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/simple-datatables/style.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/theme/assets/img/LOGO ETUS.png') }}"
        media="(prefers-color-scheme: light)">

    <title>تسجيل دفتر المقاعد</title>

</head>

<body class="toggle-sidebar">
    <header id="header" class="header fixed-top d-flex align-items-center justify-content-center">

        <style>
            @font-face {
                font-family: 'Tajwal';
                src: url('{{ asset('theme/fonts/tajwal/Tajawal-Light.ttf') }}') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
        </style>

        <div class="d-flex flex-column align-items-center" style="width: 66%;">
            <p class="logo d-flex align-items-center"
                style="width: 100%;text-align: center;justify-content: center;gap: 60px;">
                <img src="/LOGO ETUS.png" alt="">
                <span class="d-none d-lg-block"
                    style="font-family: 'Tajwal', sans-serif;text-align: end;font-size:16px;">المؤسسة العمومية للنقل
                    الحضري<br> والشبه الحضري سيدي بلعباس</span>
                <span class="d-none d-lg-block"
                    style="font-family: 'Tajwal', sans-serif;text-align: end;font-size:16px;">مديرية النقل <br>
                    ولاية سيدي بلعباس</span>
            </p>
            {{-- <i class="bi bi-list toggle-sidebar-btn"></i> --}}
        </div>


    </header>

    <main id="main" class="main" style="justify-items: center;">
        <div class="d-flex" style="align-items: center;justify-content: center;flex-direction:column;">
            <h1 class="mb-5 mt-5 pt-1"
                style="font-family: 'Tajwal', sans-serif;font-size:32px;text-align: center;font-weight:bold;">
                التسجيل الالكتروني للحصول على دفتر المقاعد <br>
                للراغبين في مزاولة نشاط سائق سيارة الأجرة <br>
                2025
            </h1>
        </div>
        <style>
            label {
                inset-inline-end: auto !important;
            }

            input[type="submit"] {
                display: block;
                width: 60%;
                background: linear-gradient(135deg, #007bff, #0056b3);

                color: #fff;
                font-size: 18px;
                padding: 12px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background 0.3s ease, transform 0.3s ease;
            }

            input[type="submit"]:hover {
                background: linear-gradient(135deg, #3399ff, #0069d9);
                transform: translateY(-2px);
            }

            input[type="submit"]:active {
                background: linear-gradient(135deg, #0056b3, #003f7f);
                transform: translateY(0);
            }
        </style>
        
        <div style="text-align: center; margin-top: 50px;">
    <h2 style="color: green;">✔ ثم التسجيل بنجاح</h2>
    <p>يرجى الانتظار قليلاً، سيتم تحميل الوصل تلقائيًا.</p>
</div>

<script>
    window.onload = function() {
        window.open("{{ route('inscription_taxi.download', $taxi->id) }}", "_blank");
    };
</script>
    </main>
</body>
<script src="/theme/tomselect/tom-select.complete.min.js"></script>
<script src="/theme/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="/theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/theme/assets/vendor/chart.js/chart.umd.js"></script>
<script src="/theme/assets/vendor/echarts/echarts.min.js"></script>
<script src="/theme/assets/vendor/quill/quill.js"></script>
<script src="/theme/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="/theme/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="/theme/assets/vendor/php-email-form/validate.js"></script>
<script src="/theme/assets/js/main.js"></script>

</html>
