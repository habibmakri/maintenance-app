@extends('inscription_formation.base_insc')
@section('title', 'الدخول إلى حساب المؤسسة')
@section('content')

    <style>
        <style>@font-face {
            font-family: 'Tajwal';
            src: url('{{ asset('theme/fonts/tajwal/Tajawal-Light.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
    </style>
    <main id="main" class="main" style="justify-items: center;">
        <div class="d-flex" style="align-items: center;justify-content: center;flex-direction:column;">
            <h1 class="mb-5 mt-5  pt-1"
                style="font-family: 'Tajwal', sans-serif;font-size:32px;text-align: center;font-weight:bold;">
                الدخول إلى حساب مؤسستكم لتسجيل العمال في مركز التكوين <br>
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
        <form class="row g-3 mx-auto" action="" method="post" dir="rtl">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @csrf
            <div class="col-md-12">
                <div class="form-floating">
                    <input name="email" type="email" value="{{ old('email') }}" required class="form-control" id="email"
                        placeholder=" ">
                    <label for="email">البريد الإلكتروني</label>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-floating">
                    <input name="password" type="password" class="form-control" id="password" placeholder="••••••••"
                        pattern="^(?=.*\d).{6,}$"
                        title="كلمة المرور يجب أن تحتوي على 6 أحرف على الأقل ورقم واحد على الأقل">
                    <label for="password">كلمة المرور</label>
                </div>
            </div>
            <div style="display: flex; justify-content: center;">
                <input type="submit" id="submit" value="تسجيل الدخول">
            </div>

        </form>
    </main>
@endsection
