@extends('inscription_formation.base_insc')
@section('title', 'قتح حساب مؤسسة')
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
                قتح حساب مؤسسة لتسجيل العمال في مركز التكوين <br>
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
            <div class="col-md-8">
                <div class="form-floating">
                    <input name="name" type="text" value="{{ old('name') }}" class="form-control" required
                        id="name" placeholder="المؤسسة" pattern="^[A-Za-zÀ-ÿ\s\-']+$"
                        title="الرجاء استخدام الحروف اللاتينية فقط">
                    <label for="name">المؤسسة</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input name="activity" type="text" value="{{ old('activity') }}" class="form-control" required
                        id="activity" placeholder="نشاط المؤسسة" >
                    <label for="activity">نشاط المؤسسة</label>
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-floating">
                    <input name="gerant" type="text" value="{{ old('gerant') }}" class="form-control" required
                        id="gerant" placeholder="المسير" pattern="^[A-Za-zÀ-ÿ\s\-']+$"
                        title="الرجاء استخدام الحروف اللاتينية فقط">
                    <label for="gerant">المسير</label>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-floating">
                    <input name="adresse" type="text" value="{{ old('adresse') }}" class="form-control" required
                        id="adresse" placeholder="العنوان" pattern="^[A-Za-zÀ-ÿ\s\-']+$"
                        title="الرجاء استخدام الحروف اللاتينية فقط">
                    <label for="adresse">العنوان</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input name="nrc" type="text" value="{{ old('nrc') }}" class="form-control" required
                        id="nrc" placeholder="رقم السجل التجاري" >
                    <label for="nrc">رقم السجل التجاري</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input name="nif" type="text" value="{{ old('nif') }}" class="form-control" required
                        id="nif" placeholder="الرقم التعريفي الجبائي">
                    <label for="nif">الرقم التعريفي الجبائي NIF </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input name="nis" type="text" value="{{ old('nis') }}" class="form-control" required
                        id="nis" placeholder="الرقم التعريفي الإحصائي" >
                    <label for="nis">الرقم التعريفي الإحصائي NIS </label>
                </div>
            </div>
           

            <div class="col-md-6">
                <div class="form-floating">
                    <input name="phone" type="phone" value="{{ old('phone') }}" class="form-control" required
                        id="phone" placeholder="0600000000" pattern="^0[1-9][0-9]{7,10}$"
                        oninput="this.value = this.value.slice(0, 10)">
                    <label for="phone">رقم الهاتف</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating">
                    <input name="email" type="email" value="{{ old('email') }}" required class="form-control" id="email"
                        placeholder=" ">
                    <label for="email">البريد الإلكتروني</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating">
                    <input name="password" type="password" class="form-control" id="password" placeholder="••••••••"
                        pattern="^(?=.*\d).{6,}$"
                        title="كلمة المرور يجب أن تحتوي على 6 أحرف على الأقل ورقم واحد على الأقل">
                    <label for="password">كلمة المرور</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating">
                    <input name="password_confirmation" type="password" class="form-control" id="password_confirmation"
                        placeholder="••••••••" pattern="^(?=.*\d).{6,}$"
                        title="الرجاء إعادة إدخال كلمة المرور للتأكيد (6 أحرف على الأقل ورقم)">
                    <label for="password_confirmation">تأكيد كلمة المرور</label>
                </div>
            </div>

            <div style="display: flex; justify-content: center;">
                <input type="submit" id="submit" value="سجل">
            </div>

        </form>
        <script>
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("password_confirmation");

            confirmPassword.addEventListener("input", function() {
                if (confirmPassword.value !== password.value) {
                    confirmPassword.setCustomValidity("كلمة المرور غير متطابقة");
                } else {
                    confirmPassword.setCustomValidity("");
                }
            });
        </script>
    </main>
@endsection
