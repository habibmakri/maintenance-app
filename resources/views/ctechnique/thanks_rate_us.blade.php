<!DOCTYPE html>
<html lang="en">
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/boxicons/css/boxicons.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.snow.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/quill/quill.bubble.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/remixicon/remixicon.css') }}" >
  <link rel="stylesheet" href="{{ asset('/theme/assets/vendor/simple-datatables/style.css') }}" >
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/theme/assets/img/LOGO ETUS.png') }}"
  media="(prefers-color-scheme: light)">
</head>
<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="card mb-3">
                <div class="card-body">
                  <div class="d-flex justify-content-center py-4">
                    <span  class="logo d-flex align-items-center w-auto">
                      <img style="max-height: 75px;margin-right: 10px;" src="{{ asset('/LOGO ETUS.png') }}" alt="">
                      <span class="d-lg-block" style="font-family: 'Tajwal', sans-serif;text-align: end;cursor:default;">المؤسسة العمومية للنقل الحضري والشبه الحضري سيدي بلعباس</span>
                    </span>
                </div>
                <div style="display: flex;flex-direction: column;">
                    <span class="d-lg-block" style="font-family: 'Tajwal', sans-serif;text-align: center;cursor:default;font-size: 42px;font-weight: 700;color: #012970;">شكرا</span>
                    <span class="d-lg-block" style="font-family: 'Tajwal', sans-serif;text-align: center;cursor:default;font-size: 26px;font-weight: 700;color: #012970;">نستفيد من أرائكم لتحسين خدماتنا</span>
                </div>
                 


          
                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->
  <script src="/theme/assets/vendor/apexcharts/apexcharts.min.js"></script> 
  <script src="/theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/theme/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="/theme/assets/vendor/echarts/echarts.min.js"></script>
  <script src="/theme/assets/vendor/quill/quill.js"></script>
  <script src="/theme/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/theme/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="/theme/assets/vendor/php-email-form/validate.js"></script>
  <script src="/theme/assets/js/main.js"></script>
  <script>
    setTimeout(function() {
      window.location.replace("https://etus22.dz");
    }, 3000);
</script>
</body>
</html>