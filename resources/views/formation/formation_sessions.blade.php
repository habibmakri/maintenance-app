@extends('base')
@section('title', 'Sessions de foramtion')
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
    <style>
        /* Make Tom Select input larger */
        .ts-wrapper {
            font-size: 1.2rem;
            /* Increase text size */
            min-height: 45px;
            /* Increase input height */
        }

        /* Black font color inside the input */
        .ts-wrapper .ts-control {
            color: black;
            background-color: #fff;
        }

        /* Black font in dropdown options */
        .ts-wrapper .ts-dropdown .ts-dropdown-content .option {
            color: black;
            font-size: 1.1rem;
        }

        /* Ensure selected items are black too */
        .ts-wrapper .item {
            color: black;
        }
    </style>
    <div class="pagetitle">
        <h1>Sessions de foramtion</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Formaion</li>
                <li class="breadcrumb-item active">Sessions de foramtion</li>
            </ol>
        </nav>
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ExtralargeModal1"
                onclick='handlecreateclick("taxis")'>
                إفتتاح دورة سيارات أجرة
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ExtralargeModal1"
                onclick='handlecreateclick("tper")'>
                إفتتاح دورة نقل أشخاص
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ExtralargeModal1"
                onclick='handlecreateclick("tmar")'>
                إفتتاح دورة نقل البضائع
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ExtralargeModal1"
                onclick='handlecreateclick("tdan")'>
                إفتتاح دورة نقل مواد خطرة
            </button>
        </div>
    </div>
    <div class="modal fade" id="ExtralargeModal1" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="validation_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" dir="rtl">
                    <form class="row g-3" action="{{ route('app.formation.create_foramtion_sessions') }}" method="post">
                        @csrf
                        <input type="hidden" name="type_insc" id="type_insc_input">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input name="date_debut" id="dateInput" type="date" required class="form-control"
                                    style="text-align: end;">
                                <label for="date">تاريخ البداية</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input name="date_fin" id="dateInput" type="date" required class="form-control"
                                    style="text-align: end;">
                                <label for="date">تاريخ النهاية</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check form-switch" style="padding-left: 0em;">
                                <label class="form-check-label" for="toggleparticipants">المشاركين:</label>
                                <select class="select" name="participants[]" id="participants" multiple
                                    aria-label="autorisations" style="height: 100px;">
                                </select>
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
                <th style="text-align: right;">التكوين</th>
                <th style="text-align: right;">رقم الدورة</th>
                <th style="text-align: right;">عدد المنخرطين</th>
                <th style="text-align: right;">تاريخ التأكيد</th>
                <th style="text-align: right;">العمليات</th>
            </tr>
        </thead>
        <tbody dir="rtl">
            @foreach ($lists as $list)
                @php
                    $type = $list->type;
                    $members = $list->count_models($type)->get();
                    $count = $members->count();
                @endphp
                <tr>
                    <td style="text-align: right;">{{ $list->id }}</td>
                    @if ($list->type == 'taxis')
                        <td style="text-align: right;">دفتر مقاعد</td>
                    @elseif($list->type == 'tper')
                        <td style="text-align: right;">نقل الأشخاص</td>
                    @elseif($list->type == 'tmar')
                        <td style="text-align: right;">نقل البضائع</td>
                    @elseif($list->type == 'tdan')
                        <td style="text-align: right;">نقل المواد الخطرة</td>
                    @endif
                    <td style="text-align: right;">{{ $list->counter }}</td>
                    <td style="text-align: right;">{{ $count }}</td>
                    <td style="text-align: right;">{{ $list->valid_date }}</td>
                    <td style="text-align:right ;">
                        @if ($list->valid_date == null)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal1"
                                onclick='handleconfirmclick(@json($list))'>تأكيد</button>
                        @else
                            <button type="button" class="btn btn-secondary" disabled data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal1" onclick=''>تأكيد</button>
                        @endif
                        <button type="button" class="btn btn-primary" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#ExtralargeModal2"
                            onclick='handledetailclick(@json($list),{{ $count }},@json($members))'>التفاصيل</button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="modal fade" id="ExtralargeModal2" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="detail_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('app.formation.print_list_session') }}" method="post">
                    @csrf
                    <input type="hidden" name="session_id" id="detail_id">
                    <div class="d-flex mt-5"
                        style="flex-direction: row;justify-content: space-around;margin-bottom:20px;">

                        {{-- @if ($list != null) --}}
                        <h5 style="font-family: 'Tajwal';">رقم اللائحة:
                            <span style="font-weight: bold;" id="detail_name"></span>
                        </h5>
                        <h5 style="font-family: 'Tajwal';">عدد المترشحين:
                            <span style="font-weight: bold;" id="detail_participants"></span>
                        </h5>
                        <h5 style="font-family: 'Tajwal';">تاريخ التأكيد:
                            <span style="font-weight: bold;" id="detail_confirmdate"></span>
                        </h5>
                    </div>

                    <div class="table-responsive px-4 mb-3">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table">
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>اللقب</th>
                                    <th>رقم التسجيل</th>
                                    <!-- Ajoute d'autres colonnes selon ton modèle -->
                                </tr>
                            </thead>
                            <tbody id="detail_members">
                                <!-- Les membres seront insérés ici par JS -->
                            </tbody>
                        </table>
                    </div>
                    {{-- @endif --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                        <button type="submit" class="btn btn-primary">طباعة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let tomSelectInstance = null;

        const participantsData = {
            taxis: @json($taxis),
            tper: @json($tper),
            tmar: @json($tmar),
            tdan: @json($tdan)
        };

        function handlecreateclick(type) {
            document.getElementById('type_insc_input').value = type;
            if (tomSelectInstance) {
                tomSelectInstance.destroy();
                tomSelectInstance = null;
            }
            const select = document.getElementById('participants');
            select.innerHTML = '';
            participantsData[type].forEach(participant => {
                const option = document.createElement('option');
                option.value = participant.id;
                option.text = `${participant.nom_ar} ${participant.prenom_ar}`;
                select.appendChild(option);
            });
            if (tomSelectInstance) {
                tomSelectInstance.destroy();
            }
            tomSelectInstance = new TomSelect('#participants', {
                plugins: ['remove_button'],
                create: false,
                maxItems: null,
                placeholder: 'اختر المشاركين',
                searchField: ['text']
            });
            const titleMap = {
                taxis: "Ouverture session formation Taxis",
                tper: "Ouverture session formation Transport personnes",
                tmar: "Ouverture session formation Transport marchandise",
                tdan: "Ouverture session formation Transport materieaux dangereux"
            };
            document.getElementById('validation_title').innerText = titleMap[type];
        }

        function handleconfirmclick(taxi) {
            const modal_title = document.getElementById('confirm_title');
            const confirm_name = document.getElementById('confirm_name');
            const confirm_id = document.getElementById('confirm_id');

            modal_title.innerHTML = '';
            confirm_id.value = taxi.id;
            modal_title.innerHTML = 'confirm list: ' + taxi.counter;
            confirm_name.innerHTML = '';
            confirm_name.innerHTML = ' ' + taxi.counter;
        }

        function handledetailclick(taxi, number, members) {
            const modal_title = document.getElementById('detail_title');
            const detail_name = document.getElementById('detail_name');
            const detail_confirmdate = document.getElementById('detail_confirmdate');
            const detail_participants = document.getElementById('detail_participants');
            const detail_id = document.getElementById('detail_id');

            modal_title.innerHTML = '';
            detail_id.value = taxi.id;
            modal_title.innerHTML = 'detail list: ' + taxi.counter;
            detail_name.innerHTML = '';
            detail_name.innerHTML = ' ' + taxi.counter;
            detail_confirmdate.innerHTML = '';
            detail_confirmdate.innerHTML = ' ' + taxi.valid_date;
            detail_participants.innerHTML = '';
            detail_participants.innerHTML = ' ' + number;


            const tbody = document.getElementById("detail_members");
            tbody.innerHTML = ""; // Vider les anciennes lignes

            members.forEach((member, index) => {
                const row = document.createElement("tr");

                row.innerHTML = `
                <td>${index + 1}</td>
                <td>${member.nom_ar ?? ''}</td>
                <td>${member.prenom_ar ?? ''}</td>
                <td>${member.validation_number ?? ''}</td>
            `;
                tbody.appendChild(row);
            });
        }
    </script>
@endsection
