<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  
  
  
  <!-- JavaScript for Tom Select -->
  <link href="{{ asset('/theme/tomselect/tom-select.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('/theme/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/boxicons/css/boxicons.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.snow.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.bubble.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/remixicon/remixicon.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/simple-datatables/style.css') }}" >
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/theme/assets/img/LOGO ETUS.png') }}" media="(prefers-color-scheme: light)">
  
  <title>@yield('title')</title>
  
</head>
{{-- <body class="toggle-sidebar"> --}}
    <body >
        @include('header')
        @include('side')
        <main id="main" class="main" >
            @yield('content')
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