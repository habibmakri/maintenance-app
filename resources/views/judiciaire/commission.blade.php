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
            <div class="text-start">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#ExtralargeModal2">إضافة لجنة
                </button>
            </div>
            <table class="table datatable mt-1" dir="rtl" style="text-align: right;">
                <thead dir="rtl">
                    <tr>
                        <th style="text-align: right;">الرقم</th>
                        <th style="text-align: right;">التاريخ</th>
                        <th style="text-align: right;">الوقت</th>
                        <th style="text-align: right;">الأعضاء</th>
                        <th style="text-align: left;">عمليات</th>
                    </tr>
                </thead>
                <tbody dir="rtl">
                    @foreach ($commissionsthisyear as $commission)
                        <tr
                            @if ($commission->caat) style="border-color: green;" @else style="border-color: red;" @endif>
                            <td>{{ date('Y', strtotime($commission->date_fiche)) }}/{{ $commission->number }}</td>
                            <td>{{ $commission->date }}</td>
                            <td>{{ $commission->time }}</td>
                            <td>{{ $commission->date }}</td>
                            <td>{{ $commission->members }}</td>
                            <td style="text-align:left ;">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal1"
                                    onclick="handleresoudreclick({{ $commission }},{{ $commission->ligne }})">تقرير</button>
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" action="" method="post">
                                @csrf
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input name="date" id="dateInput" type="date" required
                                            class="form-control" style="text-align: end;" value="{{ old('date') }}">
                                        <label for="date">اليوم</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input name="time" type="time" required class="form-control"
                                            style="text-align: end;" value="{{ old('date') }}">
                                        <label for="date">الوقت</label>
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
                                <h5 style="font-family: 'Tajwal'">الحوادث: <span
                                        style="font-weight: bold;" id="day"></span></h5>
                                @foreach ($declarations as $declaration)
                                    <div class="d-flex pb-2" style="justify-content: space-around;align-items: center;border-bottom:solid black 1px">
                                        <p>{{ $declaration->chauffeur->name }}</p>
                                        <p>{{ $declaration->time_day }}</p>
                                        <p>{{ $declaration->bus->name }}</p>
                                        <div class="form-floating">
                                            <select class="form-select" name="responsability[]" required>
                                                <option value="" disabled selected>المسؤولية</option>
                                                <option value="رئيس اللجنة"> من السائق </option>
                                                <option value="رئيس اللجنة"> ليس من السائق </option>
                                            </select>
                                            <label for="responsability">المسؤولية</label>
                                        </div>
                                        <div class="form-floating">
                                            <select class="form-select" name="decision[]" required>
                                                <option value="" disabled selected>إختر العقوبة</option>
                                                <option value="رئيس اللجنة"> للحفظ </option>
                                                <option value="رئيس اللجنة"> إنذار كتابي </option>
                                                <option value="رئيس اللجنة"> إنذار كتابي + اقتطاع منحة الحوادث </option>
                                                <option value="رئيس اللجنة"> إيقاف عن العمل مدة يوم </option>
                                                <option value="رئيس اللجنة"> إيقاف عن العمل مدة يومين </option>
                                                <option value="رئيس اللجنة"> إيقاف عن العمل مدة ثلاثة أيام </option>
                                            </select>
                                            <label for="decision">العقوبة</label>
                                        </div>
                                    </div>
                                @endforeach
                                <input type="hidden" name="fichedeclaration_id" id="fichedeclaration_id">
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
                            <h5 class="modal-title" id="modal_title"> </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h5 style="font-family: 'Tajwal'">سيدي بلعباس يوم: <span style="font-weight: bold;"
                                    id="declaration_date"></span></h5>
                            <div class="d-flex" style="flex-direction: row;justify-content: space-around;">
                                <h5 style="font-family: 'Tajwal'">الحافلة: <span style="font-weight: bold;"
                                        id="bus"></span></h5>
                                <h5 style="font-family: 'Tajwal'">السائق: <span style="font-weight: bold;"
                                        id="chauffeur"></span></h5>
                                <h5 style="font-family: 'Tajwal'">الخط: <span style="font-weight: bold;"
                                        id="ligne"></span></h5>
                            </div>
                            <div class="d-flex" style="flex-direction: row;justify-content: space-around;">
                                <h5 style="font-family: 'Tajwal'">الوقت: <span style="font-weight: bold;"
                                        id="time"></span></h5>
                                <h5 style="font-family: 'Tajwal'">اليوم: <span style="font-weight: bold;"
                                        id="day"></span></h5>
                                <h5 style="font-family: 'Tajwal'">المكان: <span style="font-weight: bold;"
                                        id="place"></span></h5>
                            </div>
                            <div class="d-flex" style="flex-direction: row;justify-content: space-around;">
                                <h5 style="font-family: 'Tajwal'">تصريح لدى CAAT: <span style="font-weight: bold;"
                                        id="caat"></span></h5>
                                <h5 style="font-family: 'Tajwal'">مصاريف: <span style="font-weight: bold;"
                                        id="paye"></span></h5>
                            </div>

                            <h5 style="font-family: 'Tajwal'">الوصف: <br><span style="font-weight: bold;"
                                    id="description"></span></h5>
                            <h5 style="font-family: 'Tajwal'">الخسائر: <br><span style="font-weight: bold;"
                                    id="pertes"></span></h5>
                            <div id="photos" style="font-family: 'Tajwal';font-weight: bold;">

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
                        <th style="text-align: right;">التاريخ</th>
                        <th style="text-align: right;">الوقت</th>
                        <th style="text-align: right;">الأعضاء</th>
                        <th style="text-align: left;">عمليات</th>
                    </tr>
                </thead>
                <tbody dir="rtl">
                    @foreach ($commissions as $commission)
                        <tr
                            @if ($commission->caat) style="border-color: green;" @else style="border-color: red;" @endif>
                            <td>{{ date('Y', strtotime($commission->date_fiche)) }}/{{ $commission->number }}</td>
                            <td>{{ $commission->date }}</td>
                            <td>{{ $commission->time }}</td>
                            <td>{{ $commission->date }}</td>
                            <td>{{ $commission->members }}</td>
                            <td style="text-align:left ;">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal1"
                                    onclick="handleresoudreclick({{ $commission }},{{ $commission->ligne }})">تقرير</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>


    <script>
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
                <input type="number" class="form-control" name="member_quantities[]" min="1" required>
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
    </script>
@endsection
