<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>قيمني</title>
    <!-- Favicons -->
    {{-- <link href="assets/img/favicon.png" rel="icon"> --}}
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Tajwal';
            src: url('{{ asset('theme/fonts/tajwal/Tajawal-Light.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Scheherazad';
            src: url('{{ asset('theme/fonts/Scheherazade_New/ScheherazadeNew-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('/theme/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/boxicons/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.bubble.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/remixicon/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/simple-datatables/style.css') }}">

</head>

<body>
    <div class="d-flex" style="align-items: center;justify-content: center;">
        <h1 class="mb-2 pt-5" style="font-family: 'Tajwal', sans-serif;font-size:30px;">المؤسسة العمومية للنقل
            الحضري<br> والشبه الحضري سيدي بلعباس</span>
            <h1 class="mb-2 pt-5" style="font-family: 'Tajwal', sans-serif;font-size:30px;">وكالة المراقبة
                التقنية<br>سيدي بلعباس</span>
                <h1 class="mb-2 pt-5" style="font-family: 'Scheherazad', sans-serif;cursor:default; font-size:50px;">
                    قيمني</span>
    </div>
    <main class="d-flex justify-content-center align-items-center vh-100" style="color: #012970;">
        <div class="container text-center px-4">
            <h1 class="mb-2 pt-5">قيمني</h1>

            <p>كيف تقيم الخدمة بشكل عام</p>
            <div class="stars my-3">
                <i class="ri-emotion-unhappy-line mx-4" style="font-size: 3.5rem;"></i>
                <i class="ri-emotion-normal-line mx-4" style="font-size: 3.5rem;"></i>
                <i class="ri-emotion-happy-line mx-4" style="font-size: 3.5rem;"></i>
            </div>

            <p>مدى رضاك عن تعامل المراقب</p>
            <div class="stars my-3">
                <i class="ri-emotion-unhappy-line mx-4" style="font-size: 3.5rem;"></i>
                <i class="ri-emotion-normal-line mx-4" style="font-size: 3.5rem;"></i>
                <i class="ri-emotion-happy-line mx-4" style="font-size: 3.5rem;"></i>
            </div>

            <p>مدى رضاك عن نظافة الوكالة</p>
            <div class="stars my-3">
                <i class="ri-emotion-unhappy-line mx-4" style="font-size: 3.5rem;"></i>
                <i class="ri-emotion-normal-line mx-4" style="font-size: 3.5rem;"></i>
                <i class="ri-emotion-happy-line mx-4" style="font-size: 3.5rem;"></i>
            </div>

            <p>مدى رضاك عن التنظيم</p>
            <div class="stars my-3">
                <i class="ri-emotion-unhappy-line mx-4" style="font-size: 3.5rem;"></i>
                <i class="ri-emotion-normal-line mx-4" style="font-size: 3.5rem;"></i>
                <i class="ri-emotion-happy-line mx-4" style="font-size: 3.5rem;"></i>
            </div>

            <p class="mt-4">هل تريد إخبارنا بأي شيء؟</p>
            <textarea class="form-control my-3" rows="3" placeholder="اكتب رسالتك هنا"></textarea>

            <p class="mt-4">رقم الهاتف</p>
            <input type="text" class="form-control my-3" placeholder="أدخل رقم الهاتف">
        </div>
    </main>

    <script src="/theme/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/theme/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="/theme/assets/vendor/echarts/echarts.min.js"></script>
    <script src="/theme/assets/vendor/quill/quill.js"></script>
    <script src="/theme/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="/theme/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="/theme/assets/vendor/php-email-form/validate.js"></script>
    <script src="/theme/assets/js/main.js"></script>

</body>

</html>
