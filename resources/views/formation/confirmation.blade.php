@extends('base')
@section('title', 'Extincteurs')
@section('content')

    <div class="pagetitle">
        <h1>Taxis</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Formaion</li>
                <li class="breadcrumb-item active">Taxis</li>
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


    <table class="table datatable mt-1" dir="rtl" style="text-align: right;">
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
                                    data-bs-target="#ExtralargeModal4" onclick='handleconfirmclick(@json($taxi))'>قبول
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
    <div class="modal fade" id="ExtralargeModal4" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="confirm_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('app.formation.confirmer_taxi') }}" method="post">
                    @csrf
                    <input type="hidden" name="taxi_id" id="confirm_id">
                    @if ($list != null)
                    <h5 style="font-family: 'Tajwal';margin-right:50px;" class="mt-4 mb-4">هل أنت متـأكد من قبول المترشح <span style="font-weight: bold;" id="confirm_name"></span> 
                        وإدراجه في اللائحة {{ $list->counter}}: <span style="font-weight: bold;" id="rejet_name"></span>
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
                        <span style="font-weight: bold;" id="rejet_name"></span></h5>
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
                        <h5 style="font-family: 'Tajwal'">صنف الرخصة: <span style="font-weight: bold;"
                                id="detail_tpermis"></span>
                        </h5>
                        <h5 style="font-family: 'Tajwal'">رقم الرخصة: <span style="font-weight: bold;"
                                id="detail_npermis"></span></h5>
                        <h5 style="font-family: 'Tajwal'">تاريخ الحصول على الرخصة: <span style="font-weight: bold;"
                                id="detail_dpermis"></span></h5>
                        <h5 style="font-family: 'Tajwal'">مسلمة من طرف: <span style="font-weight: bold;"
                                id="detail_lpermis"></span></h5>
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
            const detail_dpermis = document.getElementById('detail_dpermis');
            const detail_lpermis = document.getElementById('detail_lpermis');
            const detail_npermis = document.getElementById('detail_npermis');
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
            detail_lpermis.innerHTML = '';
            detail_lpermis.innerHTML = taxi.lieu_permis;
            detail_dpermis.innerHTML = '';
            detail_dpermis.innerHTML = taxi.date_permis;
            detail_npermis.innerHTML = '';
            detail_npermis.innerHTML = taxi.n_permis;
            detail_tpermis.innerHTML = '';
            // detail_tpermis.innerHTML = taxi.type_permis;
        }
    </script>
@endsection
