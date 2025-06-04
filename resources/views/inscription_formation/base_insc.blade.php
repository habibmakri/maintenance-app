<!DOCTYPE html>
<html lang="ar" dir="rtl">
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

    <title>@yield('title')</title>

</head>
{{-- <body class="toggle-sidebar"> --}}

<body>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: #f9f9f9;
            text-align: center;
        }

        /* header {
            background-color: #afd4fc;
            color: white;
            padding: 30px 0;
        } */

        .welcome {
            margin-top: 60px;
            margin-bottom: 40px;
        }

        .btn-start {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 15px 30px;
            font-size: 20px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-start:hover {
            background: linear-gradient(135deg, #3399ff, #0069d9);
            transform: translateY(-2px);
        }

        footer {
            margin-top: 60px;
            padding: 20px;
            background-color: #f1f1f1;
            font-size: 14px;
        }

         .custom-tab-btn {
                font-family: 'Tajwal', sans-serif;
                font-weight: 600;
                font-size: 18px;
                color: #012970;
                border: 2px solid #012970;
                border-radius: 10px;
                background-color: transparent;
                padding: 10px 25px;
                transition: all 0.3s ease-in-out;
            }

            .custom-tab-btn:hover,
            .custom-tab-btn:focus {
                background-color: #012970;
                color: white;
            }

            .custom-tab-btn.active {
                background-color: #012970;
                color: white;
            }

            .tab-card {
                direction: rtl;
                font-family: 'Tajwal', sans-serif;
                background-color: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin-bottom: 20px;
                transition: transform 0.3s ease;
            }

            .tab-card:hover {
                transform: translateY(-5px);
            }

            .tab-card h1 {
                font-size: 24px;
                color: #012970;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .tab-card p {
                font-size: 16px;
                color: #444;
                margin-bottom: 20px;
            }

            .tab-card a {
                text-decoration: none;
                color: white;
                background-color: #012970;
                padding: 10px 20px;
                border-radius: 8px;
                font-size: 16px;
                transition: background-color 0.3s;
                display: inline-block;
            }

            .tab-card a:hover {
                background-color: #001f4d;
            }

        @font-face {
            font-family: 'Tajwal';
            src: url('{{ asset('theme/fonts/tajwal/Tajawal-Light.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
    @include('inscription_formation.header_insc')
    
    @yield('content')
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
