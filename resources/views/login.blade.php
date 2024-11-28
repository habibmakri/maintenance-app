<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Se-connecter</title>
  <link rel="stylesheet" href="{{ asset('/NiceAdmin/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('/NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('/NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" >
  <link rel="stylesheet" href="{{ asset('/NiceAdmin/assets/vendor/boxicons/css/boxicons.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('/NiceAdmin/assets/vendor/quill/quill.snow.css') }}" >
  <link rel="stylesheet" href="{{ asset('/NiceAdmin/assets/vendor/quill/quill.bubble.css') }}" >
  <link rel="stylesheet" href="{{ asset('/NiceAdmin/assets/vendor/remixicon/remixicon.css') }}" >
  <link rel="stylesheet" href="{{ asset('/NiceAdmin/assets/vendor/simple-datatables/style.css') }}" >

</head>
<body>
   <h1>Welcome to the Admin Dashboard</h1>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Floating labels Form</h5>
      
      <!-- Floating Labels Form -->
      <form class="row g-3" action="" method="post">
        @csrf
        @error('email')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ $message }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @enderror
        <div class="col-md-12">
          <div class="form-floating">
            <input type="email" class="form-control" name="email" id="email" @if($user->email!="") value="{{ old('email', $user->email)}}" @endif>
            <label for="email">Email</label>
          </div>
        </div>
        
        <div class="col-md-12">
          <div class="form-floating">
            <input type="password" class="form-control" name="password" id="Password" placeholder="Password">
            <label for="password">Password</label>
          </div>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary">Submit</button>
          <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
      </form>
      
    </div>
  </div>
  <script src="/NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js"></script> 
  <script src="/NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/NiceAdmin/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="/NiceAdmin/assets/vendor/echarts/echarts.min.js"></script>
  <script src="/NiceAdmin/assets/vendor/quill/quill.js"></script>
  <script src="/NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="/NiceAdmin/assets/vendor/php-email-form/validate.js"></script>
  <script src="/NiceAdmin/assets/js/main.js"></script>

</body>
</html>