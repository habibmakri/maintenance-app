@extends('base')
@section('title', $type_insc)
@section('content')
    <style>
        @font-face {
            font-family: 'lateef';
            src: url('{{ asset('theme/fonts/lateef/Lateef-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        label {
            inset-inline-end: auto !important;
        }
    </style>

    <div class="pagetitle">
        <h1>{{ $type_insc }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Formaion</li>
                <li class="breadcrumb-item active">{{ $type_insc }}</li>
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


    <table class="table datatable mt-1" dir="rtl" style="text-align: right;font-family: 'Tajwal';">
        <thead dir="rtl">
            <tr>
                <th style="text-align: right;">الرقم</th>
                <th style="text-align: right;">
                    الأسم
                </th>
                <th style="text-align: right;">النشاط </th>
                <th style="text-align: right;">المسير</th>
                <th style="text-align: right;">العنوان</th>
                <th style="text-align: right;">رقم الهاتف</th>
                <th style="text-align: right;">الإيمايل</th>
                <th style="text-align: right;">العمليات</th>
            </tr>
        </thead>
        <tbody dir="rtl">
            @foreach ($taxis as $taxi)
                <tr>
                    <td style="text-align: right;">{{ $taxi->id }}</td>
                    <td style="text-align: right;">{{ $taxi->name }}</td>
                    <td style="text-align: right;">{{ $taxi->activity }}</td>
                    <td style="text-align: right;">{{ $taxi->gerant }}</td>
                    <td style="text-align: right;">{{ $taxi->adresse }}</td>
                    <td style="text-align: right;">{{ $taxi->phone }}</td>
                    <td style="text-align: right;">{{ $taxi->email }}</td>
                    <td style="text-align:left ;">
                        <div class="d-flex gap-2"style="justify-content: flex-end;">
                            @if ($taxi->waiting_status == true)
                                <form action="{{ route('app.formation.print_entrepise_details') }}"method="post">
                                    @csrf
                                    <input type="hidden" name="id_entreprise" value="{{ $taxi->id }}">
                                    <button type="submit" class="btn btn-success">
                                        إرفاق فاتورة شكلية
                                    </button>
                                </form>
                            @endif
                            @if ($taxi->waiting_status == true)
                                <form action="{{ route('app.formation.print_entrepise_details') }}"method="post">
                                    @csrf
                                    <input type="hidden" name="id_entreprise" value="{{ $taxi->id }}">
                                    <button type="submit" style="background-color: rgb(123, 36, 126);color:white;" class="btn">
                                        طباعة معلومات
                                    </button>
                                </form>
                            @endif
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal1"
                                onclick='handleresoudreclick(@json($taxi), @json($type_insc))'>مستحقات</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal2"
                                onclick='handledetailclick(@json($taxi), @json($type_insc))'>معلومات</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="ExtralargeModal1" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="validation_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" dir="rtl">
                    <form class="row g-3" action="{{ route('app.formation.valider_transport') }}" method="post">
                        @csrf
                        <div class="col-md-12">
                            <input type="hidden" name="type_insc" id="type_insc_input">
                            <input type="hidden" name="id_participant" id="id_participant">
                            <h4 style="font-family: 'Tajwal';">تاريخ دفع المستحقات</h4>
                            <div class="form-floating">
                                <input name="date" id="dateInput" type="date" required class="form-control"
                                    style="text-align: end;">
                                <label for="date">اليوم</label>
                            </div>
                            <h4 style="font-family: 'Tajwal';">المبلغ المدفوع</h4>
                            <div class="form-floating">
                                <input name="somme_paiement" id="somme_paiement" type="numeric" step="10" required
                                    class="form-control" style="text-align: start;">
                                <label for="somme_paiement">المبلغ المدفوع</label>
                            </div>
                            <h4 class="mt-4" style="font-family: 'Tajwal';">رقم وصل البنك / أمر بالدفع</h4>
                            <div class="form-floating">
                                <input name="cheque_number" id="cheque_number" type="text" required
                                    class="form-control" style="text-align: start;">
                                <label for="cheque_number">الرقم</label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                            <button type="submit" class="btn btn-primary">تأكيد</button>
                        </div>
                    </form>
                </div>
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
                        <h5 style="font-family: 'Tajwal'">إسم المؤسسة: <span style="font-weight: bold;"
                                id="detail_name"></span></h5>
                        <h5 style="font-family: 'Tajwal'">إسم المسير: <span style="font-weight: bold;"
                                id="detail_gerant"></span></h5>
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
                    <h5 style="font-family: 'Tajwal';font-weight: bold;">العمال المسجلين:
                    </h5>
                    <div class="table-responsive px-4 mb-3">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table">
                                <tr id="detail_table_header">

                                </tr>
                            </thead>
                            <tbody id="detail_table_content">
                            </tbody>
                        </table>
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
        function handleresoudreclick(taxi, type_insc) {
            const modal_title = document.getElementById('validation_title');
            const type_insc_input = document.getElementById('type_insc_input');
            const id_participant = document.getElementById('id_participant');
            modal_title.innerHTML = '';
            modal_title.innerHTML = 'Validation ' + type_insc + ': ' + taxi.nom_fr + ' ' + taxi.prenom_fr;
            type_insc_input.innerHTML = '';
            type_insc_input.value = type_insc;
            id_participant.innerHTML = '';
            id_participant.value = taxi.id;
        }

        function handledetailclick(taxi, type_insc) {
            const modal_title = document.getElementById('detail_title');
            const type_insc_input = document.getElementById('type_insc_input');
            const detail_name = document.getElementById('detail_name');
            const detail_gerant = document.getElementById('detail_gerant');
            const detail_email = document.getElementById('detail_email');
            const detail_phone = document.getElementById('detail_phone');
            const detail_adresse = document.getElementById('detail_adresse');
            const detail_table_header = document.getElementById('detail_table_header');
            const detail_table_content = document.getElementById('detail_table_content');
            modal_title.innerHTML = '';
            modal_title.innerHTML = 'Detail inscription ' + type_insc + ': ' + taxi.name;
            type_insc_input.innerHTML = '';
            type_insc_input.value = type_insc;
            detail_name.innerHTML = '';
            detail_name.innerHTML = taxi.name;
            detail_gerant.innerHTML = '';
            detail_gerant.innerHTML = taxi.gerant;
            detail_adresse.innerHTML = '';
            detail_adresse.innerHTML = taxi.adresse;
            detail_phone.innerHTML = '';
            detail_phone.innerHTML = taxi.phone;
            detail_email.innerHTML = '';
            detail_email.innerHTML = taxi.email;
            detail_table_header.innerHTML = '';
            detail_table_content.innerHTML = '';
            detail_table_header.innerHTML = `  <th>#</th>
                                        <th>الاسم واللقب</th>
                                        <th>تاريخ  ومكان الإزدياد</th>
                                        <th>نوع التسجيل</th>`;
            i = 0;
            taxi.count_tper_emps.forEach((e) => {
                i = i + 1;
                detail_table_content.innerHTML += `
                <tr>
                    <td>${i}</td>    
                    <td>${e.nom_ar} ${e.prenom_ar}</td>    
                    <td>${e.birthdate} ${e.birthplace}</td>    
                    <td>نقل الأشخاص</td>    
                </tr>
                `;
            });
            taxi.count_tmar_emps.forEach((e) => {
                i = i + 1;
                detail_table_content.innerHTML += `
                <tr>
                    <td>${i}</td>    
                    <td>${e.nom_ar} ${e.prenom_ar}</td>    
                    <td>${e.birthdate} ${e.birthplace}</td>    
                    <td>نقل البضائع</td>    
                </tr>
                `;
            });
            taxi.count_tdan_emps.forEach((e) => {
                i = i + 1;
                detail_table_content.innerHTML += `
                <tr>
                    <td>${i}</td>    
                    <td>${e.nom_ar} ${e.prenom_ar}</td>    
                    <td>${e.birthdate} ${e.birthplace}</td>    
                    <td>نقل المواد الخطرة</td>    
                </tr>
                `;
            });

        }
    </script>
@endsection
