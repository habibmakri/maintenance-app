@extends('base')
@section('title', 'Commissions Judiciare')
@section('content')
    <style>
        label {
            inset-inline-end: auto !important;
        }
    </style>
    <div class="pagetitle">
        <h1>Commissions d'Accidents</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Judiciare</li>
                <li class="breadcrumb-item ">Commission</li>
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

    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist" dir="rtl">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">هذه السنة</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">الكل</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent" style = "font-family: 'Tajwal';">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab" dir="rtl">
            <div class="text-start">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#ExtralargeModal2">إضافة لجنة
                </button>
            </div>
            <table class="table datatable mt-1" dir="rtl" style="text-align: right;">
                <thead dir="rtl">
                    <tr>
                        <th style="text-align: right;">الرقم</th>
                        <th style="text-align: right;">التاريخ الوقت</th>
                        <th style="text-align: right;">الأعضاء</th>
                        <th style="text-align: left;">عمليات</th>
                    </tr>
                </thead>
                <tbody dir="rtl">
                    @foreach ($commissionsthisyear as $commission)
                        <tr
                            @if ($commission->caat) style="border-color: green;" @else style="border-color: red;" @endif>
                            <td>{{ date('Y', strtotime($commission->date)) }}/{{ $commission->number }}</td>
                            <td>{{ $commission->date . ' - ' . $commission->time }}</td>
                            <td>
                                @php
                                    $members = json_decode($commission->members, true);
                                @endphp
                                @if ($members)
                                    @foreach ($members as $name => $role)
                                        <strong>{{ $name }}</strong>: {{ $role }}<br>
                                    @endforeach
                                @else
                                    No members
                                @endif
                            </td>
                            <td style="text-align:left ;">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal1"
                                    onclick="handleresoudreclick({{ $commission }},{{ $commission->declarations }})">تقرير</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="modal fade" id="ExtralargeModal2" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header" dir="ltr">
                            <h5 class="modal-title" id="modal_title">Nouvelle Commission D'accident</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" action="" method="post">
                                @csrf
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input name="date" id="dateInput" type="date" required class="form-control"
                                            style="text-align: end;" value="{{ old('date') }}">
                                        <label for="date">اليوم</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <input name="time" type="time" required class="form-control"
                                            style="text-align: end;" value="{{ old('date') }}">
                                        <label for="date">وقت البداية</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <input name="timeend" type="time" required class="form-control"
                                            style="text-align: end;" value="{{ old('date') }}">
                                        <label for="date">وقت النهاية</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" required id="floatingNumber"
                                            name="number" placeholder="الرقم" value="{{ old('number') }}"
                                            maxlength="3">
                                        <label for="floatingNumber">الرقم</label>
                                    </div>
                                </div>
                                <h5 style="font-family: 'Tajwal'">الأعضاء: <span style="font-weight: bold;"
                                        id="day"></span></h5>
                                <div id="members-section">

                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-secondary btn-sm" id="add-member">
                                        إضافة عضو
                                    </button>
                                </div>
                                <h5 style="font-family: 'Tajwal'">الحوادث: <span style="font-weight: bold;"
                                        id="day"></span></h5>
                                @foreach ($declarations as $declaration)
                                    <div class="d-flex pb-2 align-items-center"
                                        style="justify-content: space-around; border-bottom: solid black 1px">
                                        <p class="mb-0 w-15 text-truncate">
                                            @if ($declaration->chauffeur->id == 80)
                                                {{ explode(':', $declaration->description)[0] . ' - ' }}
                                            @endif {{ $declaration->chauffeur->name }}
                                        </p>
                                        <p class="mb-0">{{ $declaration->time_day }}</p>
                                        <p class="mb-0">{{ $declaration->bus->name }}</p>

                                        <div>
                                            <label for="check_{{ $declaration->id }}">تفعيل </label>
                                            <input type="checkbox" class="form-check-input toggle-fields"
                                                data-id="{{ $declaration->id }}" id="check_{{ $declaration->id }}">
                                        </div>
                                        <input type="hidden" name="declaration_ids[]"
                                            id="declaration_{{ $declaration->id }}" value="{{ $declaration->id }}"
                                            disabled>
                                        <div class="form-floating">
                                            <select class="form-select responsability" name="responsability[]"
                                                id="responsability_{{ $declaration->id }}" disabled>
                                                <option value="" disabled selected>المسؤولية</option>
                                                <option value="true"> من السائق </option>
                                                <option value="false"> ليس من السائق </option>
                                            </select>
                                            <label for="responsability_{{ $declaration->id }}">المسؤولية</label>
                                        </div>

                                        <div class="form-floating">
                                            <select class="form-select decision" name="decision[]"
                                                id="decision_{{ $declaration->id }}" disabled>
                                                <option value="" disabled selected>إختر العقوبة</option>
                                                <option value="للحفظ"> للحفظ</option>
                                                <option value="إنذار كتابي"> إنذار كتابي</option>
                                                <option value="إنذار كتابي + اقتطاع منحة الحوادث"> إنذار كتابي + اقتطاع
                                                    منحة الحوادث</option>
                                                <option value="إيقاف عن العمل مدة يوم"> إيقاف عن العمل مدة يوم</option>
                                                <option value="إيقاف عن العمل مدة يومين"> إيقاف عن العمل مدة يومين</option>
                                                <option value="إيقاف عن العمل مدة ثلاثة أيام"> إيقاف عن العمل مدة ثلاثة
                                                    أيام</option>
                                            </select>
                                            <label for="decision_{{ $declaration->id }}">العقوبة</label>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                                    <button type="submit" class="btn btn-primary">تأكيد</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="ExtralargeModal1" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header" dir="ltr">
                            <h5 class="modal-title" id="modal_title_rapport"> </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex" style="flex-direction: row;justify-content: space-around;">
                                <h5 style="font-family: 'Tajwal'">رقم: <span style="font-weight: bold;"
                                        id="declaration_number"></span></h5>
                                <h5 style="font-family: 'Tajwal'">سيدي بلعباس يوم: <span style="font-weight: bold;"
                                        id="declaration_date"></span></h5>
                            </div>
                            <div class="d-flex" style="flex-direction: row;justify-content: space-around;">
                                <h5 style="font-family: 'Tajwal'">وقت البداية: <span style="font-weight: bold;"
                                        id="begintime"></span></h5>
                                <h5 style="font-family: 'Tajwal'">وقت النهاية: <span style="font-weight: bold;"
                                        id="endtime"></span></h5>
                            </div>
                            <h4 class="text-center" style="font-family: 'Tajwal';font-weight: bold;">الأعضاء</h4>

                            <div class="d-flex"
                                id="members_container"style="flex-direction: row;justify-content: space-around;">

                            </div>
                        </div>
                        <h4 class="text-center" style="font-family: 'Tajwal';font-weight: bold;">الحوادث</h4>

                        <div class="d-flex" id="accidents_container" style="flex-direction: column;">

                        </div>

                        <form class="row g-3" action="" method="post">
                            @csrf
                            <input type="hidden" name="fichedeclaration_id" id="fichedeclaration_id">
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

    <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab" dir="rtl">
        <table class="table datatable mt-1" dir="rtl" style="text-align: right;">
            <thead dir="rtl">
                <tr>
                    <th style="text-align: right;">الرقم</th>
                    <th style="text-align: right;">التاريخ الوقت</th>
                    <th style="text-align: right;">الأعضاء</th>
                    <th style="text-align: left;">عمليات</th>
                </tr>
            </thead>
            <tbody dir="rtl">
                @foreach ($commissions as $commission)
                    <tr
                        @if ($commission->caat) style="border-color: green;" @else style="border-color: red;" @endif>
                        <td>{{ date('Y', strtotime($commission->date)) }}/{{ $commission->number }}</td>
                        <td>{{ $commission->date . ' - ' . $commission->time }}</td>
                        <td>
                            @php
                                $members = json_decode($commission->members, true);
                            @endphp
                            @if ($members)
                                @foreach ($members as $name => $role)
                                    <strong>{{ $name }}</strong>: {{ $role }}<br>
                                @endforeach
                            @else
                                No members
                            @endif
                        </td>
                        <td style="text-align:left ;">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal1"
                                onclick="handleresoudreclick2({{ $commission }},{{ $commission->ligne }})">تقرير</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    </div>


    <script>
        function handleresoudreclick(declaration, judiciairedeclarations) {
            console.log(declaration);
            const modal_title = document.getElementById('modal_title_rapport');
            const declarationdate = document.getElementById('declaration_date');
            const declarationIdInput = document.getElementById('fichedeclaration_id');
            const declarationnumber = document.getElementById('declaration_number');
            const memberscontainer = document.getElementById('members_container');
            const accidentscontainer = document.getElementById('accidents_container');
            const begintime = document.getElementById('begintime');
            const endtime = document.getElementById('endtime');
            const dateObj = new Date(declaration.date);
            const formattedDate =
                `${String(dateObj.getDate()).padStart(2, '0')}-${String(dateObj.getMonth() + 1).padStart(2, '0')}-${dateObj.getFullYear()}`;
            const bus = document.getElementById('bus');
            modal_title.innerHTML = '';
            declarationdate.innerHTML = '';
            declarationnumber.innerHTML = '';
            memberscontainer.innerHTML = '';
            accidentscontainer.innerHTML = '';
            begintime.innerHTML = '';
            endtime.innerHTML = '';
            modal_title.innerHTML = 'Commission D\'accident n°' + declaration.number + '-' +
                dateObj.getFullYear() + ' le ' + declaration.date;
            declarationIdInput.value = declaration.id;
            declarationnumber.innerHTML = declaration.number + '-' + dateObj.getFullYear();
            declarationdate.innerHTML = formattedDate;
            begintime.innerHTML = declaration.time;
            endtime.innerHTML = declaration.endtime;
            let members = JSON.parse(declaration.members);

            Object.entries(members).forEach(([name, role]) => {
                const div = document.createElement("div");
                div.innerHTML = `<strong>${name}</strong>: ${role}`;
                div.classList.add("mb-2");
                memberscontainer.appendChild(div);
            });
            judiciairedeclarations.forEach(e => {
                const div = document.createElement("div");
                let responsabilite = e.responsability ? "من سائق" : "ليس من سائق";
                let chauffeurName = e.chauffeur.name;
                let role = "سائق";
                if (e.id_chauffeur == 80 && e.description) {
                    chauffeurName = e.description.split(':')[0] + ' - ' + chauffeurName;
                    responsabilite = e.responsability ? "من عامل" : "ليس من العامل";
                    role = "عامل";
                }
                div.innerHTML = `
        <div class="d-flex flex-row justify-content-around align-items-center w-100">
            <span><strong>${role}</strong> ${chauffeurName}</span>
            <span><strong>الحافلة:</strong> ${e.bus.name}</span>
            <span><strong>المسؤولية:</strong> ${responsabilite}</span>
            <span><strong>قرار الجنة:</strong> ${e.decision}</span>
        </div>
    `;
                div.classList.add("mb-2", "p-3");
                accidentscontainer.appendChild(div);
            });

        }




        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("dateInput").value = new Date().toISOString().split('T')[0];
        });
        document.getElementById('add-member').addEventListener('click', function() {
            const toolsSection = document.getElementById('members-section');
            const toolDiv = document.createElement('div');
            toolDiv.classList.add('row', 'g-3', 'mb-3', 'align-items-center');
            const selectDiv = document.createElement('div');
            selectDiv.classList.add('col-md-6');
            selectDiv.innerHTML = `
            <div class="form-floating">
                <select class="form-select" name="members[]" required>
                    <option value="" disabled selected>إختر الصفة</option>
                    <option value="رئيس اللجنة"> رئيس اللجنة </option>
                    <option value="عضو"> عضو </option>
                    <option value="كاتب"> كاتب </option>
                </select>
                <label for="members">member</label>
            </div>
        `;
            const numberDiv = document.createElement('div');
            numberDiv.classList.add('col-md-5');
            numberDiv.innerHTML = `
            <div class="form-floating">
                <input type="text" class="form-control" name="member_quantities[]" required>
                <label for="member_quantities">الإسم واللقب</label>
            </div>
        `;
            const deleteDiv = document.createElement('div');
            deleteDiv.classList.add('col-md-1', 'text-center');
            deleteDiv.innerHTML = `
            <button type="button" class="btn btn-danger btn-sm remove-member">إلغاء</button>
        `;
            toolDiv.appendChild(selectDiv);
            toolDiv.appendChild(numberDiv);
            toolDiv.appendChild(deleteDiv);
            toolsSection.appendChild(toolDiv);
            deleteDiv.querySelector('.remove-member').addEventListener('click', function() {
                toolDiv.remove();
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".toggle-fields").forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    let id = this.dataset.id;
                    let hiddenInput = document.getElementById("declaration_" + id);
                    let responsability = document.getElementById("responsability_" + id);
                    let decision = document.getElementById("decision_" + id);

                    if (this.checked) {
                        hiddenInput.disabled = false;
                        responsability.disabled = false;
                        responsability.required = true;
                        decision.disabled = false;
                        decision.required = true;
                    } else {
                        hiddenInput.disabled = true;
                        responsability.disabled = true;
                        responsability.required = false;
                        responsability.value = "";
                        decision.disabled = true;
                        decision.required = false;
                        decision.value = "";
                    }
                });
            });
        });
    </script>
@endsection
