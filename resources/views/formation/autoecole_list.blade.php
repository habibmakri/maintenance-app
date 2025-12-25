@extends('base')
@section('title', 'Listes des Auto Ecole')
@section('content')

    <div class="pagetitle">
        <h1>Listes des Auto Ecole</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Formaion</li>
                <li class="breadcrumb-item active">Listes des Auto Ecole</li>
            </ol>
        </nav>
        <div class="text-end">
            @if ($allConfirmed)
                <a href="{{ route('app.formation.create_list_autoecole') }}" class="btn btn-primary">
                    Nouvelle Liste
                </a>
            @else
                <button href="" disabled class="btn btn-primary">
                    Nouvelle Liste
                </button>
            @endif
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
                <th style="text-align: right;">
                    رقم اللائحة
                </th>
                <th style="text-align: right;">تاريخ التأكيد</th>
                <th style="text-align: right;">عدد المنخرطين</th>
                <th style="text-align: right;">العمليات</th>
            </tr>
        </thead>
        <tbody dir="rtl">
            @foreach ($lists as $list)
                <tr>
                    <td style="text-align: right;">{{ $list->id }}</td>
                    <td style="text-align: right;">{{ $list->counter }}</td>
                    <td style="text-align: right;">{{ $list->valid_date }}</td>
                    <td style="text-align: right;">{{ $list->count_taxis->count() }}</td>
                    <td style="text-align:right ;">
                        @if ($list->valid_date == null)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal1"
                                onclick='handleconfirmclick(@json($list))'>تأكيد</button>
                            <button type="button" class="btn btn-secondary" disabled class="btn btn-danger"
                                data-bs-toggle="modal" data-bs-target="#ExtralargeModal2">التفاصيل</button>
                        @else
                            <button type="button" class="btn btn-secondary" disabled data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal1" onclick=''>تأكيد</button>
                            <button type="button" class="btn btn-primary" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal2" onclick='handledetailclick(@json($list),{{$list->count_taxis->count()}})'>التفاصيل</button>
                        @endif

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
                    <h5 class="modal-title" id="confirm_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('app.formation.do_confirmer_list_autoecole') }}" method="post">
                    @csrf
                    <input type="hidden" name="list_id" id="confirm_id">
                    {{-- @if ($list != null) --}}
                        <h5 style="font-family: 'Tajwal';margin-right:50px;" class="mt-4 mb-4">هل أنت متـأكد من اللائحة
                            <span style="font-weight: bold;" id="confirm_name"></span>
                        </h5>
                    {{-- @endif --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                        <button type="submit" class="btn btn-primary">تأكيد</button>
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
                <form action="{{ route('app.formation.print_list_taxis') }}" method="post">
                    @csrf
                    <input type="hidden" name="list_id" id="detail_id">
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
                        {{-- @endif --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">غلق</button>
                        <button type="submit" class="btn btn-primary">طباعة</button>
                    </div>
                </form>
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
            modal_title.innerHTML = 'confirm list: ' + taxi.counter;
            confirm_name.innerHTML = '';
            confirm_name.innerHTML = ' ' + taxi.counter;
        }
        function handledetailclick(taxi,number) {
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
        }
    </script>
@endsection
