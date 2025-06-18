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
                <th style="text-align: right;">اللقب</th>
                <th style="text-align: right;">تاريخ ومكان الميلاد</th>
                <th style="text-align: right;">العنوان</th>
                <th style="text-align: right;">رقم الهاتف</th>
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
                    <td style="text-align:left ;">
                        <button type="button"
                            @if ($taxi->payment_number != null) class="btn btn-success" disabled @else class="btn btn-danger" @endif
                            data-bs-toggle="modal" data-bs-target="#ExtralargeModal1"
                            onclick='handleresoudreclick(@json($taxi), @json($type_insc))'>مستحقات</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#ExtralargeModal2" onclick="">معلومات</button>
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
                    <form class="row g-3" action="{{route('app.formation.valider_transport')}}" method="post">
                        @csrf
                        <div class="col-md-12">
                            <h4 style="font-family: 'Tajwal';" >تاريخ دفع المستحقات</h4>
                            <input type="hidden" name="type_insc" id="type_insc_input">
                            <input type="hidden" name="id_participant" id="id_participant">
                            <div class="form-floating">
                                <input name="date" id="dateInput" type="date" required class="form-control"
                                    style="text-align: end;">
                                <label for="date">اليوم</label>
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
    </script>
@endsection
