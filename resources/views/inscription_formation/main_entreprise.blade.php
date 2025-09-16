@extends('inscription_formation.base_insc')
@section('title', 'حساب مؤسسة' . $entreprise->name)
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
                مرحبا في فضاء مؤسسة {{ $entreprise->name }}
            </h1>
        </div>
        <style>
            label {
                inset-inline-end: auto !important;
            }

            input[type="button"] {
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

            input[type="button"]:hover {
                background: linear-gradient(135deg, #3399ff, #0069d9);
                transform: translateY(-2px);
            }

            input[type="button"]:active {
                background: linear-gradient(135deg, #0056b3, #003f7f);
                transform: translateY(0);
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
        <section class="section" style="width: 100%;">
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
            <div class="row">

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"
                                style="text-align: center;font-family: 'Tajwal', sans-serif;font-weight:bold;">معلومات
                                المؤسسة</h5>
                            <p><strong>إسم المؤسسة: </strong>{{ $entreprise->name }}</p>
                            <p><strong>مسير المؤسسة: </strong>{{ $entreprise->gerant }}</p>
                            <p><strong>عنوان المؤسسة: </strong>{{ $entreprise->adresse }}</p>
                            <p><strong>البريد الالكتروني الخاص بالمؤسسة: </strong>{{ $entreprise->email }}</p>
                            <p><strong>رقم السجل التجاري: </strong>{{ $entreprise->nrc }}</p>
                            <p><strong>الرقم التعريفي الجبائي: </strong>{{ $entreprise->nif }}</p>
                            <p><strong>الرقم التعريفي الإحصائي: </strong>{{ $entreprise->nis }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card"style="height: 60vh;overflow-y:scroll;">
                        <div class="card-body">
                            <h5 class="card-title"
                                style="text-align: center;font-family: 'Tajwal', sans-serif;font-weight:bold;">العمال
                                المسجلين</h5>
                            <table class="table datatable mt-1" dir="rtl"
                                style="text-align: right;font-family: 'Tajwal';">
                                <thead dir="rtl">
                                    <tr>
                                        <th style="text-align: right;">
                                            الرقم
                                        </th>
                                        <th style="text-align: right;">
                                            العامل
                                        </th>
                                        <th style="text-align: right;">نوع التكوين</th>
                                        <th style="text-align: right;">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody dir="rtl">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($entreprise->count_tper_emps as $emp)
                                        <td style="text-align: right;">
                                            {{ $i }}
                                        </td>
                                        <td style="text-align: right;">
                                            {{ $emp->nom_ar . ' ' . $emp->prenom_ar }}
                                        </td>
                                        <td style="text-align: right;">نقل الأشخاص</td>
                                        @if ($emp->payment_number == null)
                                            <td style="text-align: right;">مستحقات غير مدفوعة</td>
                                        @else
                                            <td style="text-align: right;">مستحقات مدفوعة</td>
                                        @endif
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                    @foreach ($entreprise->count_tmar_emps as $emp)
                                        <tr>
                                            <td style="text-align: right;">
                                                {{ $i }}
                                            </td>
                                            <td style="text-align: right;">
                                                {{ $emp->nom_ar . ' ' . $emp->prenom_ar }}
                                            </td>
                                            <td style="text-align: right;">نقل البضائع</td>
                                            @if ($emp->payment_number == null)
                                                <td style="text-align: right;">مستحقات غير مدفوعة</td>
                                            @else
                                                <td style="text-align: right;">مستحقات مدفوعة</td>
                                            @endif
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                    @foreach ($entreprise->count_tdan_emps as $emp)
                                        <tr>
                                            <td style="text-align: right;">
                                                {{ $i }}
                                            </td>
                                            <td style="text-align: right;">
                                                {{ $emp->nom_ar . ' ' . $emp->prenom_ar }}
                                            </td>
                                            <td style="text-align: right;">نقل المواد الخطرة</td>
                                            @if ($emp->payment_number == null)
                                                <td style="text-align: right;">مستحقات غير مدفوعة</td>
                                            @else
                                                <td style="text-align: right;">مستحقات مدفوعة</td>
                                            @endif
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($i == 1)
                                <div style="display: flex; justify-content: center;">
                                    <input type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#ExtralargeModal1" value="إظافة عامل">
                                </div>
                            @else
                                @if ($entreprise->waiting_status == false)
                                    <div style="display: flex; justify-content: center; gap: 15px;">
                                        <input type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#ExtralargeModal1" value="إظافة عامل">
                                        <form action="{{ route('inscription.demande_proformat') }}"  method="post">
                                            @csrf
                                            <input type="submit" style="width: 100%" class="btn btn-primary" value="طلب فاتورة شكلية ">
                                        </form>
                                    </div>
                                @else
                                    <div style="display: flex; justify-content: center; gap: 15px;">
                                        <input type="button" disabled class="btn btn-primary" data-bs-toggle="modal"
                                            value="ثم تأكيد طلبكم ">
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="ExtralargeModal1" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header" dir="ltr">
                            <h5 class="modal-title" id="modal_title"> </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3 mx-auto" action="{{ route('inscription.add_entreprise_emp') }}"
                                method="post" dir="rtl">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <select class="form-select" required name="type_insc" id="type_insc"
                                            placeholder="type_insc" aria-label="Floating label select example">
                                            <option value="" selected disabled> </option>
                                            <option value="tper">نقل الأشخاص</option>
                                            <option value="tmar">نقل البضائع</option>
                                            <option value="tdan">نقل المواد الخطرة</option>
                                        </select>
                                        <label for="type_insc">نوع التكوين</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" required name="gender" id="gender"
                                            placeholder="gender" aria-label="Floating label select example">
                                            <option value="homme" selected>السيد</option>
                                            <option value="femme">السيدة</option>
                                        </select>
                                        <label for="gender"></label>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input name="nin" type="number" value="{{ old('nin') }}"
                                            class="form-control" required id="nin" placeholder="XXXXXXXXXX"
                                            pattern="^\d{18}$" oninput="this.value = this.value.slice(0, 18)">
                                        <label for="nin">رقم التعريف الوطني</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="nom_ar" type="text" value="{{ old('nom_ar') }}"
                                            class="form-control" required id="nom_ar" placeholder="اللقب"
                                            pattern="^[\u0600-\u06FF\s]+$"
                                            title="Veuillez entrer uniquement des lettres arabes.">
                                        <label for="nom_ar">اللقب</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="prenom_ar" type="text" value="{{ old('prenom_ar') }}"
                                            class="form-control" required id="prenom_ar" placeholder="الإسم"
                                            pattern="^[\u0600-\u06FF\s]+$"
                                            title="Veuillez entrer uniquement des lettres arabes.">
                                        <label for="prenom_ar">الإسم</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="nom_fr" type="text" value="{{ old('nom_fr') }}"
                                            class="form-control" id="nom_fr" placeholder="Nom en français"
                                            pattern="^[A-Za-zÀ-ÿ\s\-']+$"
                                            title="الرجاء إدخال اللقب باستخدام الحروف اللاتينية فقط">
                                        <label for="nom_fr">اللقب بالفرنسية</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="prenom_fr" type="text" value="{{ old('prenom_fr') }}"
                                            class="form-control" id="prenom_fr" placeholder="Prénom en français"
                                            pattern="^[A-Za-zÀ-ÿ\s\-']+$"
                                            title="الرجاء إدخال الاسم باستخدام الحروف اللاتينية فقط">
                                        <label for="prenom_fr">الإسم بالفرنسية</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="birthdate" type="date" required class="form-control"
                                            id="birthdate" style="text-align: end;" value="{{ old('birthdate') }}">
                                        <label for="birthdate">تاريخ الميلاد</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="birthplace" type="text" value="{{ old('birthplace') }}"
                                            class="form-control" required id="birthplace" placeholder="مكان الميلاد">
                                        <label for="birthplace">مكان الميلاد</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input name="adresse" type="text" value="{{ old('adresse') }}"
                                            class="form-control" required id="adresse" placeholder="العنوان">
                                        <label for="adresse">العنوان</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input name="phone" type="phone" value="{{ old('phone') }}"
                                            class="form-control" required id="phone" placeholder="0600000000"
                                            pattern="^0[5-7][0-9]{8}$" oninput="this.value = this.value.slice(0, 10)">
                                        <label for="phone">رقم الهاتف</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input name="email" type="email" value="{{ old('email') }}"
                                            class="form-control" id="email" placeholder=" ">
                                        <label for="email">البريد الإلكتروني اختياري</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <select class="form-select" required name="type_permis" id="type_permis"
                                            aria-label="بلدية ممارسة النشاط">
                                            {{-- <option value="" disabled {{ old('type_permis') ? '' : 'selected' }}>اختر الصنف</option> --}}
                                            <option value="D" {{ old('type_permis') == 'D' ? 'selected' : '' }}>
                                                D | د
                                            </option>
                                            <option value="C1" {{ old('type_permis') == 'C1' ? 'selected' : '' }}>
                                                C1 | ج1
                                            </option>
                                            <option value="C2" {{ old('type_permis') == 'C2' ? 'selected' : '' }}>
                                                C2 | ج2
                                            </option>
                                            <option value="C1-C2" {{ old('type_permis') == 'C1-C2' ? 'selected' : '' }}>
                                                C1-C2 | ج1-ج2
                                            </option>
                                        </select>
                                        <label for="type_permis">صنف الرخصة</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="n_permis" type="text" value="{{ old('n_permis') }}"
                                            class="form-control" required id="n_permis" placeholder="رقم رخصة السياقة">
                                        <label for="n_permis">رقم رخصة السياقة</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="date_permis" type="date" required class="form-control"
                                            id="date_permis" style="text-align: end;" value="{{ old('date_permis') }}">
                                        <label for="date_permis">تاريخ الحصول على رخصة السياقة</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input name="lieu_permis" type="text" value="{{ old('lieu_permis') }}"
                                            class="form-control" required id="lieu_permis"
                                            placeholder="بلدية صدور رخصة السياقة">
                                        <label for="lieu_permis">بلدية صدور رخصة السياقة</label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                                    <button type="submit" class="btn btn-primary">طباعة</button>
                                </div>
                            </form>

                        </div>


                    </div>
                </div>
            </div>

            </div>

        </section>

    </main>
@endsection
