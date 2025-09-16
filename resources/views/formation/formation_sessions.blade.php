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
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input name="date_debut" id="dateInput" type="date" required class="form-control"
                                    style="text-align: end;">
                                <label for="date">تاريخ البداية</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input name="date_fin" id="dateInput" type="date" required class="form-control"
                                    style="text-align: end;">
                                <label for="date">تاريخ النهاية</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input name="groups" id="groups" type="number" value="1" step="1"
                                    min="1" required class="form-control"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <label for="groups">عدد الأفواج</label>
                            </div>
                        </div>
                        <div class="row g-3" id="modules">

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
                <th style="text-align: right;">تاريخ المداولات</th>
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
                    <td style="text-align:left ;">
                        @if ($list->valid_date == null)
                            <div class="d-flex gap-2"style="justify-content: flex-end;">
                                <form action="{{ route('app.formation.print_presence_paper') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="session_id" value="{{ $list->id }}">
                                    <button type="submit" class="btn" style="background-color: rgb(38, 201, 174);color:white;" data-bs-toggle="modal">طباعة
                                        لائحات الحضور</button>
                                </form>
                                <form action="{{ route('app.formation.print_notes_paper') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="session_id" value="{{ $list->id }}">
                                    <button type="submit" class="btn" style="background-color: rgb(201, 38, 38);color:white;" data-bs-toggle="modal">طباعة
                                        لائحات النقاط</button>
                                </form>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal3" 
                                    onclick='handleconfirmclick(@json($list),@json($members))'>تأكيد</button>
                                <button type="button" class="btn btn-primary" class="btn btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#ExtralargeModal2"
                                    onclick='handledetailclick(@json($list),{{ $count }},@json($members))'>التفاصيل</button>
                            </div>
                        @else
                            <div class="d-flex gap-2"style="justify-content: flex-end;">
                                <form action="{{ route('app.formation.print_detail_session') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="session_id" value="{{ $list->id }}">
                                    <button type="submit" class="btn btn-info" data-bs-toggle="modal">طباعة
                                        التفاصيل</button>
                                </form>
                                <form action="{{ route('app.formation.print_diplomes') }}"method="post">
                                    @csrf
                                    <input type="hidden" name="session_id" value="{{ $list->id }}">
                                    <button type="submit" class="btn"  style="background-color: rgb(197, 211, 75);color:black;" data-bs-toggle="modal">طباعة 
                                        الشهادات</button>
                                </form>
                                <form action="{{ route('app.formation.print_delibiration') }}"method="post">
                                    @csrf
                                    <input type="hidden" name="session_id" value="{{ $list->id }}">
                                    <button type="submit" class="btn"  style="background-color: rgb(161, 94, 49);color:white;" data-bs-toggle="modal">طباعة نتائج
                                        المداولات</button>
                                </form>
                                <form action="{{ route('app.formation.print_delibiration_fiches') }}"method="post">
                                    @csrf
                                    <input type="hidden" name="session_id" value="{{ $list->id }}">
                                    <button type="submit" class="btn"
                                        style="background-color: rgb(123, 36, 126);color:white;"
                                        data-bs-toggle="modal">طباعة إستمارات التقييم
                                    </button>
                                </form>
                                <button type="button" class="btn btn-primary" class="btn btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#ExtralargeModal2"
                                    onclick='handledetailclick(@json($list),{{ $count }},@json($members))'>التفاصيل</button>
                            </div>
                        @endif

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
                    <div style=";max-height: 80vh;overflow-Y: scroll;">
                        @csrf
                        <input type="hidden" name="session_id" id="detail_id">
                        <div class="d-flex mt-5"
                            style="flex-direction: row;justify-content: space-around;margin-bottom:20px;">

                            {{-- @if ($list != null) --}}
                            <h5 style="font-family: 'Tajwal';">تاريخ البداية:
                                <span style="font-weight: bold;" id="detail_ddebut"></span>
                            </h5>
                            <h5 style="font-family: 'Tajwal';">تاريخ النهاية:
                                <span style="font-weight: bold;" id="detail_ddfin"></span>
                            </h5>
                        </div>
                        <div class="d-flex mt-5"
                            style="flex-direction: row;justify-content: space-around;margin-bottom:20px;">

                            {{-- @if ($list != null) --}}
                            <h5 style="font-family: 'Tajwal';">رقم اللائحة:
                                <span style="font-weight: bold;" id="detail_name"></span>
                            </h5>
                            <h5 style="font-family: 'Tajwal';">عدد المترشحين:
                                <span style="font-weight: bold;" id="detail_participants"></span>
                            </h5>
                            <h5 style="font-family: 'Tajwal';">عدد الأفواج:
                                <span style="font-weight: bold;" id="detail_groups"></span>
                            </h5>
                            <h5 style="font-family: 'Tajwal';">تاريخ المداولات:
                                <span style="font-weight: bold;" id="detail_confirmdate"></span>
                            </h5>
                        </div>

                        <div class="table-responsive px-4 mb-3">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table">
                                    <tr id="detail_table_header">
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>اللقب</th>
                                        <th>رقم التسجيل</th>
                                    </tr>
                                </thead>
                                <tbody id="detail_members">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- @endif --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                        <button type="submit" class="btn btn-primary">طباعة المنخرطين</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ExtralargeModal3" tabindex="-1"
        style="display: none; text-align: right;font-family: 'Tajwal';" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header" dir="ltr">
                    <h5 class="modal-title" id="confirm_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('app.formation.confirm_session') }}" method="post">
                    <div style=";max-height: 70vh;overflow-Y: scroll;">
                        @csrf
                        <input type="hidden" name="session_id" id="confirm_id">
                        <input type="hidden" name="session_type" id="confirm_type">
                        <div class="d-flex mt-5"
                            style="flex-direction: row;justify-content: space-around;margin-bottom:20px;">

                            {{-- @if ($list != null) --}}
                            <h5 style="font-family: 'Tajwal';">رقم اللائحة:
                                <span style="font-weight: bold;" id="confirm_name"></span>
                            </h5>
                            <h5 style="font-family: 'Tajwal';">عدد المترشحين:
                                <span style="font-weight: bold;" id="confirm_participants"></span>
                            </h5>
                            <div class="form-floating">
                                <input name="confirm_date" id="confirm_date" type="date" required
                                    class="form-control" style="text-align: end;">
                                <label for="confirm_date">تاريخ المداولات</label>
                            </div>
                        </div>

                        <div class="table-responsive px-4 mb-3">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table">
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم واللقب</th>
                                    </tr>
                                </thead>
                                <tbody id="confirm_members">
                                    <!-- Les membres seront insérés ici par JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- @endif --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                        <button type="button" class="btn btn-warning" onclick="saveDraftToServer()">حفظ</button>
                        <button type="submit" class="btn btn-primary">تأكيد</button>
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
        const modulestypes = {
            taxis: ['التنظيم المتعلق بالإستغلال', 'مبادئ في ميكانيك السيارة', 'سلوك سائق سيارة الأجرة',
                'الجغرافية المحلية', 'السير وأمن المرور', 'مبادئ في الاسعافات الاولية'
            ],
            tper: ['أبعاد النقل جانب التنظيمي بالنقل عبر الطرقات', 'المفاهيم التقنية لمركبات النقل عبر الطرقات',
                'الوقاية والسلامة عبر الطرقات', 'الإسعاف', 'التنظيم المطبق على نقل الأشخاص عبر الطرقات',
                'فن حسن التصرف', 'سلوك السائق في مركزالعمل', 'تقنيات سياقة مركبات نقل الأشخاص عبر الطرقات'
            ],
            tmar: ['أبعاد النقل جانب التنظيمي بالنقل عبر الطرقات', 'المفاهيم التقنية لمركبات النقل عبر الطرقات',
                'الوقاية والسلامة عبر الطرقات', 'الإسعاف', 'التنظيم المطبق على نقل الأشخاص عبر الطرقات',
                'فن حسن التصرف', 'سلوك السائق في مركزالعمل', 'تقنيات سياقة مركبات نقل الأشخاص عبر الطرقات'
            ],
            tdan: ['التنظيم المتعلق بنقل المواد الخطرة عبر الطرقات',
                'الشروط المرتبطة بمركبات نقل المواد الخطرة عبر الطرقات',
                'الشروط المرتبطة بالأمن أثناء نقل نقل المواد الخطرة عبر الطرقات'
            ]
        };

        function handlecreateclick(type) {
            document.getElementById('type_insc_input').value = type;
            moduleFieldsDiv = document.getElementById('modules');
            moduleFieldsDiv.innerHTML = '';

            modulestypes[type].forEach((modulename, index) => {
                const moduleDiv = document.createElement('div');
                moduleDiv.className = 'col-md-4';

                moduleDiv.innerHTML = `
                <label class="form-label mb-0 flex-shrink-0" style="white-space: nowrap; width: 90%; overflow: hidden;">الأستاذ لمادة: ${modulename}</label>
                <input type="text" name="profs[${modulename}]" class="form-control" placeholder="${modulename}" required>
            `;

                moduleFieldsDiv.appendChild(moduleDiv);
            });


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


        function handleconfirmclick(session, participants) {
            document.getElementById('confirm_id').value = session.id;
            document.getElementById('confirm_name').innerText = session.counter;
            document.getElementById('confirm_type').value = session.type;
            document.getElementById('confirm_participants').innerText = participants.length;

            const tbody = document.getElementById("confirm_members");
            const thead = document.querySelector("#confirm_members").closest("table").querySelector("thead tr");

            tbody.innerHTML = "";
            thead.innerHTML = "";

            const modules = modulestypes[session.type] || [];


            thead.innerHTML = `
        <th>#</th>
        <th>الاسم واللقب</th>
        ${modules.map(module => `<th>${module}</th>`).join('')}
    `;


            participants.forEach((member, index) => {
                const row = document.createElement("tr");
                const id = member.id;

                const notes = typeof member.notes === 'string' ? JSON.parse(member.notes) : (member.notes || {});

                const noteInputs = modules.map((module) => {
                    const modNotes = notes[module] || {};

                    console.log(
                        `ID: ${id}, Module: ${module}, إمتحان: ${modNotes['إمتحان']}, مواضبة: ${modNotes['مواضبة']}`
                    );

                    return `
            <td>
                <input type="number" name="participants[${index}][${module}][مواضبة]" 
                    class="form-control" step="0.5" min="0" max="20"
                    value="${modNotes['مواضبة'] ?? ''}" placeholder="المواضبة" required>

                <input style="margin-top:20px;" type="number" name="participants[${index}][${module}][إمتحان]" 
                    class="form-control" step="0.5" min="0" max="20"
                    value="${modNotes['إمتحان'] ?? ''}" placeholder="الإمتحان" required>
            </td>
        `;
                }).join('');

                row.innerHTML = `
        <td>${index + 1}</td>
        <td>${member.nom_ar + ' ' + (member.prenom_ar ?? '')}</td>
        <input type="hidden" name="participants[${index}][id]" value="${member.id}">
        ${noteInputs}
    `;

                tbody.appendChild(row);
            });
        }

        function saveDraftToServer() {
            const sessionId = document.getElementById("confirm_id").value;
            const sessionType = document.getElementById("confirm_type").value;

            const table = document.getElementById("confirm_members");
            const rows = table.querySelectorAll("tr");

            const participants = [];

            rows.forEach((row, i) => {
                const idInput = row.querySelector(`input[name="participants[${i}][id]"]`);
                if (!idInput) return;

                const id = idInput.value;
                const data = {
                    id
                };

                const inputs = row.querySelectorAll("input[type='number']");
                inputs.forEach(input => {
                    const name = input.name;
                    const match = name.match(/\[(.*?)\]\[(.*?)\]\[(.*?)\]/);
                    if (match) {
                        const module = match[2];
                        const type = match[3];
                        data[module] = data[module] || {};
                        data[module][type] = input.value;
                    }
                });

                participants.push(data);
            });

            fetch("{{ route('app.formation.save_draft') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        session_type: sessionType,
                        participants: participants
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert("✅ تم الحفظ مؤقتًا في قاعدة البيانات");
                    location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert("❌ فشل الحفظ المؤقت");
                });
        }

        function handledetailclick(taxi, number, members) {
            const modal_title = document.getElementById('detail_title');
            const detail_name = document.getElementById('detail_name');
            const detail_confirmdate = document.getElementById('detail_confirmdate');
            const detail_groups = document.getElementById('detail_groups');
            const detail_participants = document.getElementById('detail_participants');
            const detail_id = document.getElementById('detail_id');
            const detail_table_header = document.getElementById('detail_table_header');
            const dddebut = document.getElementById('detail_ddebut');
            const ddfin = document.getElementById('detail_ddfin');
            dddebut.innerHTML = '';
            dddebut.innerHTML = ' ' + taxi.date_debut;
            ddfin.innerHTML = '';
            ddfin.innerHTML = ' ' + taxi.date_fin;

            modal_title.innerHTML = '';
            detail_id.value = taxi.id;
            modal_title.innerHTML = 'detail Session: ' + taxi.type + ' ' + taxi.counter;
            detail_name.innerHTML = '';
            detail_name.innerHTML = ' ' + taxi.counter;
            detail_confirmdate.innerHTML = '';
            if (taxi.valid_date) {
                detail_confirmdate.innerHTML = ' ' + taxi.valid_date;
            }
            detail_participants.innerHTML = '';
            detail_participants.innerHTML = ' ' + number;
            detail_groups.innerHTML = '';
            detail_groups.innerHTML = ' ' + taxi.groups;


            const tbody = document.getElementById("detail_members");
            tbody.innerHTML = "";
            if (taxi.valid_date) {
                members.forEach((member, index) => {
                    detail_table_header.innerHTML = `
                        <th>#</th>
                        <th>الاسم</th>
                        <th>اللقب</th>
                        <th>رقم التسجيل</th>
                        <th>المعدل العام</th>
                    `;
                    const notes1 = member.notes || {};
                    let total = 0;
                    let count = 0;
                    let notes = JSON.parse(notes1)
                    for (const module in notes) {
                        const mod = notes[module];
                        const exam = parseFloat(mod["إمتحان"]) || 0;
                        const presence = parseFloat(mod["مواضبة"]) || 0;
                        if (exam > 0 || presence > 0) {
                            total += (exam + presence) / 2;
                            count++;
                        }
                    }

                    const moyenne = count > 0 ? (total / count).toFixed(2) : '';
                    const row = document.createElement("tr");
                    row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${member.nom_ar ?? ''}</td>
                    <td>${member.prenom_ar ?? ''}</td>
                    <td>${member.validation_number ?? ''}</td>
                    <td>${ moyenne ?? ''}</td> 
                `;
                    tbody.appendChild(row);
                });
            } else {
                members.forEach((member, index) => {
                    const row = document.createElement("tr");
                    detail_table_header.innerHTML = `
                        <th>#</th>
                        <th>الاسم</th>
                        <th>اللقب</th>
                        <th>رقم التسجيل</th>
                    `;
                    row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${member.nom_ar ?? ''}</td>
                    <td>${member.prenom_ar ?? ''}</td>
                    <td>${member.validation_number ?? ''}</td>
                `;
                    tbody.appendChild(row);
                });
            }

        }
    </script>
@endsection
