@extends('base')
@section('title', 'autoecole')
@section('content')

    <div class="pagetitle">
        <h1>AUTO ECOLE</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Formaion</li>
                <li class="breadcrumb-item active">AUTO ECOLE</li>
            </ol>
        </nav>
    </div>


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
    <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ExtralargeModaladd">Ajouter
        </button>
    </div>

    <table class="table datatable mt-1" dir="rtl" style="text-align: right;font-family: 'Tajwal';">
        <thead dir="rtl">
            <tr>
                <th style="text-align: right;">الرقم</th>
                <th style="text-align: right;">
                    الأسم
                </th>
                <th style="text-align: right;">اللقب</th>
                <th style="text-align: right;">تاريخ ومكان الميلاد</th>
                <th style="text-align: right;">العنوان</th>
                <th style="text-align: right;">رقم الهاتف</th>
                <th style="text-align: right;">بلديةالإستغلال</th>
                <th style="text-align: right;">العمليات</th>
            </tr>
        </thead>
        <tbody dir="rtl">
            @foreach ($taxis as $taxi)
                <tr>
                    <td style="text-align: right;">{{ $taxi->id }}</td>
                    <td style="text-align: right;">{{ $taxi->nom_ar }}</td>
                    <td style="text-align: right;">{{ $taxi->prenom_ar }}</td>
                    <td style="text-align: right;">{{ $taxi->birthdate . '  ' . $taxi->birthplace }}</td>
                    <td style="text-align: right;">{{ $taxi->adresse }}</td>
                    <td style="text-align: right;">{{ $taxi->phone }}</td>
                    <td style="text-align: right;">{{ $taxi->comune_exploi }}</td>
                    <td style="text-align:left;">
                        @if ($taxi->list == null && $taxi->rejet == false)
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal3"
                                onclick='handlerejetclick(@json($taxi))'>رفض التسجيل
                            </button>
                            @if ($list != null)
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal4"
                                    onclick='handleconfirmclick(@json($taxi))'>قبول
                                </button>
                            @endif
                        @elseif ($taxi->list == null && $taxi->rejet == true)
                            <button type="button" class="btn btn-danger" disabled data-bs-toggle="modal">مرفوض
                            </button>
                        @elseif ($taxi->list != null && $taxi->rejet == false)
                            <button type="button" class="btn btn-success" disabled data-bs-toggle="modal">مقبول لائحة
                                {{ $taxi->list_m->counter }}
                            </button>
                        @endif
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#ExtralargeModal2"
                            onclick='handledetailclick(@json($taxi))'>معلومات</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="ExtralargeModaladd" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="padding: 25px;" 25px;>
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="confirm_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="row g-3 mx-auto" style="margin-top: 25px;" action="{{ route('app.formation.ajouter_autoecole') }}" method="post" dir="rtl">
                    
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
                            <input name="nin" type="number" value="{{ old('nin') }}" class="form-control"
                                required id="nin" placeholder="XXXXXXXXXX" pattern="^\d{18}$"
                                oninput="this.value = this.value.slice(0, 18)">
                            <label for="nin">رقم التعريف الوطني</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating">
                            <input name="nom_ar" type="text" value="{{ old('nom_ar') }}" class="form-control"
                                required id="nom_ar" placeholder="اللقب" pattern="^[\u0600-\u06FF\s]+$"
                                title="Veuillez entrer uniquement des lettres arabes.">
                            <label for="nom_ar">اللقب</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating">
                            <input name="prenom_ar" type="text" value="{{ old('prenom_ar') }}" class="form-control"
                                required id="prenom_ar" placeholder="الإسم" pattern="^[\u0600-\u06FF\s]+$"
                                title="Veuillez entrer uniquement des lettres arabes.">
                            <label for="prenom_ar">الإسم</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating">
                            <input name="nom_fr" type="text" value="{{ old('nom_fr') }}" class="form-control"
                                id="nom_fr" placeholder="Nom en français" pattern="^[A-Za-zÀ-ÿ\s\-']+$"
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
                            <input name="birthplace" type="text" value="{{ old('birthplace') }}"
                                class="form-control" required id="birthplace" placeholder="مكان الميلاد">
                            <label for="birthplace">مكان الميلاد</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input name="adresse" type="text" value="{{ old('adresse') }}" class="form-control"
                                required id="adresse" placeholder="العنوان">
                            <label for="adresse">العنوان</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input name="phone" type="phone" value="{{ old('phone') }}" class="form-control"
                                required id="phone" placeholder="0600000000" pattern="^0[5-7][0-9]{8}$"
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
                    <div class="col-md-12">
                        <div class="form-floating">
                            <select class="form-select" required name="type" id="type" placeholder="type"
                                aria-label="Floating label select example">
                                <option value="الصنف" selected>الصنف</option>
                                <option value="صنف أ" >صنف أ</option>
                                <option value="صنف ب">صنف ب</option>
                                <option value="صنف ج">صنف ج</option>
                                <option value="صنف د">صنف د</option>
                            </select>
                            <label for="type">الصنف</label>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: center;">
                        <input class="btn btn-primary" type="submit" id="submit" value="سجل">
                    </div>

                    {{-- <div id="bus-form-container" class="row"></div> --}}
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ExtralargeModal4" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="confirm_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('app.formation.confirmer_autoecole') }}" method="post">
                    @csrf
                    <input type="hidden" name="taxi_id" id="confirm_id">
                    @if ($list != null)
                        <h5 style="font-family: 'Tajwal';margin-right:50px;" class="mt-4 mb-4">هل أنت متـأكد من قبول
                            المترشح
                            <span style="font-weight: bold;" id="confirm_name"></span>
                            وإدراجه في اللائحة {{ $list->counter }}: <span style="font-weight: bold;"
                                id="rejet_name"></span>
                        </h5>
                    @endif
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                        <button type="submit" class="btn btn-success">قبول</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ExtralargeModal3" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="rejet_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('app.formation.rejet_taxi') }}" method="post">
                    @csrf
                    <input type="hidden" name="taxi_id" id="rejet_id">
                    <h5 style="font-family: 'Tajwal';margin-right:50px;" class="mt-4 mb-4">هل أنت متـأكد من رفض المترشح:
                        <span style="font-weight: bold;" id="rejet_name"></span>
                    </h5>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                        <button type="submit" class="btn btn-danger">رفض</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ExtralargeModal2" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="detail_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" dir="rtl">
                    <h5 style="font-family: 'Tajwal'">تاريخ التسجيل: <span style="font-weight: bold;"
                            id="detail_date_insc"></span></h5>
                    <div class="d-flex" style="flex-direction: row;justify-content: space-around;margin-bottom:20px;">
                        <h5 style="font-family: 'Tajwal'">الإسم واللقب: <span style="font-weight: bold;"
                                id="detail_name"></span></h5>
                        <h5 style="font-family: 'Tajwal'">تاريخ ومكان الميلاد: <span style="font-weight: bold;"
                                id="detail_birth"></span></h5>
                    </div>

                    <div class="d-flex" style="flex-direction: row;justify-content: space-around;margin-bottom:20px;">
                        <h5 style="font-family: 'Tajwal'">العنوان: <span style="font-weight: bold;"
                                id="detail_adresse"></span>
                        </h5>
                        <h5 style="font-family: 'Tajwal'">رقم الهاتف: <span style="font-weight: bold;"
                                id="detail_phone"></span></h5>
                        <h5 style="font-family: 'Tajwal'">البريد الالكتروني: <span style="font-weight: bold;"
                                id="detail_email"></span></h5>
                    </div>

                    <div class="d-flex" style="flex-direction: row;justify-content: space-around;margin-bottom:20px;">
                        <h5 style="font-family: 'Tajwal'">الصنف: <span style="font-weight: bold;"
                                id="detail_tpermis"></span>
                        </h5>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                    {{-- <button type="submit" class="btn btn-primary">تأكيد</button> --}}
                </div>
            </div>
        </div>

    </div>
    <script>
        function handleconfirmclick(taxi) {
            const modal_title = document.getElementById('confirm_title');
            const confirm_name = document.getElementById('confirm_name');
            const confirm_id = document.getElementById('confirm_id');

            modal_title.innerHTML = '';
            confirm_id.value = taxi.id;
            modal_title.innerHTML = 'confirm inscription: ' + taxi.nom_fr + ' ' + taxi.prenom_fr;
            confirm_name.innerHTML = '';
            confirm_name.innerHTML = taxi.nom_ar + ' ' + taxi.prenom_ar;
        }

        function handlerejetclick(taxi) {
            const modal_title = document.getElementById('rejet_title');
            const rejet_name = document.getElementById('rejet_name');
            const rejet_id = document.getElementById('rejet_id');

            modal_title.innerHTML = '';
            rejet_id.value = taxi.id;
            modal_title.innerHTML = 'Rejet inscription: ' + taxi.nom_fr + ' ' + taxi.prenom_fr;
            rejet_name.innerHTML = '';
            rejet_name.innerHTML = taxi.nom_ar + ' ' + taxi.prenom_ar;
        }

        function handledetailclick(taxi) {
            const modal_title = document.getElementById('detail_title');
            const detail_name = document.getElementById('detail_name');
            const detail_date_insc = document.getElementById('detail_date_insc');

            const detail_email = document.getElementById('detail_email');
            const detail_phone = document.getElementById('detail_phone');
            const detail_birth = document.getElementById('detail_birth');
            const detail_adresse = document.getElementById('detail_adresse');
            const detail_tpermis = document.getElementById('detail_tpermis');
            modal_title.innerHTML = '';
            modal_title.innerHTML = 'Detail inscription: ' + taxi.nom_fr + ' ' + taxi.prenom_fr;
            detail_date_insc.innerHTML = '';
            detail_date_insc.innerHTML = taxi.inscription_time;
            detail_name.innerHTML = '';
            detail_name.innerHTML = taxi.nom_ar + ' ' + taxi.prenom_ar;
            detail_birth.innerHTML = '';
            detail_birth.innerHTML = taxi.birthdate + ' ' + taxi.birthplace;
            detail_adresse.innerHTML = '';
            detail_adresse.innerHTML = taxi.adresse;
            detail_phone.innerHTML = '';
            detail_phone.innerHTML = taxi.phone;
            detail_email.innerHTML = '';
            detail_email.innerHTML = taxi.email;
            detail_tpermis.innerHTML = '';
            detail_tpermis.innerHTML = taxi.type;
        }
    </script>
@endsection
