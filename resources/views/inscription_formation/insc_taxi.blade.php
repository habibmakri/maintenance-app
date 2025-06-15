@extends('inscription_formation.base_insc')
@section('title', 'تسجيل دفتر المقاعد')
@section('content')

    <style>
        <style>
            @font-face {
                font-family: 'Tajwal';
                src: url('{{ asset('theme/fonts/tajwal/Tajawal-Light.ttf') }}') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
        </style>
    </style>
    <main id="main" class="main" style="justify-items: center;" >
        <div class="d-flex" style="align-items: center;justify-content: center;flex-direction:column;">
            <h1 class="mb-5 mt-5  pt-1"
                style="font-family: 'Tajwal', sans-serif;font-size:32px;text-align: center;font-weight:bold;">
                التسجيل الالكتروني للحصول على دفتر المقاعد <br>
                {{-- للراغبين في مزاولة نشاط سائق سيارة الأجرة <br> --}}
            </h1>
        </div>
        {{-- <div class="alert alert-info alert-dismissible fade show mt-3" role="alert" dir="rtl">
            يجب على صاحب الطلب من أجل الحصول على دفتر مقاعد للنقل بواسطة سيارة الأجرة ان يستوفي الشروط التالية:
            <ul style="padding-right: 40px; list-style-type: square; font-size: 14px;" dir="rtl">
                <li>أن يبلغ خمسا وعشرين (25) سنة على الأقل.</li>
                <li>أن يتمتع بجميع حقوقه المدنية والوطنية.</li>
                <li>أن يكون من جنسية جزائرية.</li>
                <li>أن لا يمارس نشاطا مأجورا آخر.</li>
                <li>أن يكون حائزا على رخصة سياقة منذ سنتين على الأقل.</li>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> --}}
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
            <div class="col-md-4">
                <div class="form-floating">
                    <select class="form-select" required name="gender" id="gender" placeholder="gender"
                        aria-label="Floating label select example">
                        <option value="homme" selected>السيد</option>
                        <option value="femme">السيدة</option>
                    </select>
                    <label for="gender"></label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-floating">
                    <input name="nin" type="number" value="{{ old('nin') }}" class="form-control" required
                        id="nin" placeholder="XXXXXXXXXX" pattern="^\d{18}$"
                        oninput="this.value = this.value.slice(0, 18)">
                    <label for="nin">رقم التعريف الوطني</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating">
                    <input name="nom_ar" type="text" value="{{ old('nom_ar') }}" class="form-control" required
                        id="nom_ar" placeholder="اللقب" pattern="^[\u0600-\u06FF\s]+$"
                        title="Veuillez entrer uniquement des lettres arabes.">
                    <label for="nom_ar">اللقب</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating">
                    <input name="prenom_ar" type="text" value="{{ old('prenom_ar') }}" class="form-control" required
                        id="prenom_ar" placeholder="الإسم" pattern="^[\u0600-\u06FF\s]+$"
                        title="Veuillez entrer uniquement des lettres arabes.">
                    <label for="prenom_ar">الإسم</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating">
                    <input name="nom_fr" type="text" value="{{ old('nom_fr') }}" class="form-control" id="nom_fr"
                        placeholder="Nom en français" pattern="^[A-Za-zÀ-ÿ\s\-']+$"
                        title="الرجاء إدخال اللقب باستخدام الحروف اللاتينية فقط">
                    <label for="nom_fr">اللقب بالفرنسية</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating">
                    <input name="prenom_fr" type="text" value="{{ old('prenom_fr') }}" class="form-control"
                        id="prenom_fr" placeholder="Prénom en français" pattern="^[A-Za-zÀ-ÿ\s\-']+$"
                        title="الرجاء إدخال الاسم باستخدام الحروف اللاتينية فقط">
                    <label for="prenom_fr">الإسم بالفرنسية</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating">
                    <input name="birthdate" type="date" required class="form-control" id="birthdate"
                        style="text-align: end;" value="{{ old('birthdate') }}">
                    <label for="birthdate">تاريخ الميلاد</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating">
                    <input name="birthplace" type="text" value="{{ old('birthplace') }}" class="form-control"
                        required id="birthplace" placeholder="مكان الميلاد">
                    <label for="birthplace">مكان الميلاد</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating">
                    <input name="adresse" type="text" value="{{ old('adresse') }}" class="form-control" required
                        id="adresse" placeholder="العنوان">
                    <label for="adresse">العنوان</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating">
                    <input name="phone" type="phone" value="{{ old('phone') }}" class="form-control" required
                        id="phone" placeholder="0600000000" pattern="^0[5-7][0-9]{8}$"
                        oninput="this.value = this.value.slice(0, 10)">
                    <label for="phone">رقم الهاتف</label>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-floating">
                    <input name="email" type="email" value="{{ old('email') }}" class="form-control"
                        id="email" placeholder=" ">
                    <label for="email">البريد الإلكتروني اختياري</label>
                </div>
            </div>

            {{-- <div class="col-md-4">
                <div class="form-floating">
                    <input name="comune_exploi" type="text" value="{{ old('comune_exploi') }}" class="form-control"
                        required id="comune_exploi" placeholder="بلدية ممارسة النشاط">
                    <label for="comune_exploi">بلدية ممارسة النشاط</label>
                </div>
            </div> --}}

            {{-- <div class="col-md-4">
                <div class="form-floating">
                    <select class="form-select" required name="comune_exploi" id="comune_exploi"
                        aria-label="بلدية ممارسة النشاط">
                        <option value="" disabled {{ old('comune_exploi') ? '' : 'selected' }}>اختر بلدية
                        </option>
                        @php
                            $communes = [
                                'سيدي بلعباس',
                                'سيدي لحسن',
                                'سفيزف',
                                'تنيرة',
                                'عين البرد',
                                'سيدي براهيم',
                                'مصطفى بن براهيم',
                                'بلعربي',
                                'تلاغ',
                                'مزاورو',
                                'مرين',
                                'رأس الماء',
                                'بن باديس',
                                'بضرابين المقراني',
                                'حاسي زهانة',
                                'سيدي علي بوسيدي',
                                'لمطار',
                                'سيدي علي بن يوب',
                                'بوخنفيس',
                            ];
                        @endphp
                        @foreach ($communes as $commune)
                            <option value="{{ $commune }}"
                                {{ old('comune_exploi') == $commune ? 'selected' : '' }}>
                                {{ $commune }}
                            </option>
                        @endforeach
                    </select>
                    <label for="comune_exploi">بلدية ممارسة النشاط</label>
                </div>
            </div> --}}





            <div class="col-md-4">
                <div class="form-floating">
                    <input name="n_permis" type="text" value="{{ old('n_permis') }}" class="form-control" required
                        id="n_permis" placeholder="رقم رخصة السياقة">
                    <label for="n_permis">رقم رخصة السياقة</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-floating">
                    <input name="date_permis" type="date" required class="form-control" id="date_permis"
                        style="text-align: end;" value="{{ old('date_permis') }}">
                    <label for="date_permis">تاريخ الحصول على رخصة السياقة</label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-floating">
                    <input name="lieu_permis" type="text" value="{{ old('lieu_permis') }}" class="form-control"
                        required id="lieu_permis" placeholder="بلدية صدور رخصة السياقة">
                    <label for="lieu_permis">بلدية صدور رخصة السياقة</label>
                </div>
            </div>

            <div style="display: flex; justify-content: center;">
                <input type="submit" id="submit" value="سجل">
            </div>

            {{-- <div id="bus-form-container" class="row"></div> --}}
        </form>
    </main>
@endsection
