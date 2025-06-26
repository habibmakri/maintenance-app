@extends('base')
@section('title', 'Extincteurs')
@section('content')

    <div class="pagetitle">
        <h1>Extincteurs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                {{-- <li class="breadcrumb-item">Gestion</li> --}}
                <li class="breadcrumb-item active">Extincteurs</li>
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


    <table class="table datatable">
        <thead>
            <tr>
                <th>N°</th>
                <th>
                    Reference
                </th>
                <th>Type</th>
                <th>Affectation</th>
                <th>Date Recahrge</th>
                <th>Date d'expiration</th>
                <th>Reste</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($extincteurs as $extincteur)
                <tr>
                    {{-- <td>{{ $extincteur->id }}</td> --}}
                    <td>{{ $i }}</td>
                    <td>{{ $extincteur->reference }}</td>
                    <td>{{ $extincteur->type }}</td>
                    <td>{{ $extincteur->affectation }}</td>
                    <td>{{ $extincteur->date_recharge }}</td>
                    <td>{{ $extincteur->date_expiration }}</td>
                    @php
                        // $rechargeDate = \Carbon\Carbon::parse($extincteur->date_recharge);
                        $now = \Carbon\Carbon::now();
                        $expirationDate = \Carbon\Carbon::parse($extincteur->date_expiration);
                        $differenceInDays = floor($now->diffInDays($expirationDate, false));
                        if ($differenceInDays < 0) {
                            $cssClass = 'text-danger fw-bold';
                        } elseif ($differenceInDays < 15) {
                            $cssClass = 'text-warning fw-bold';
                        } else {
                            $cssClass = '';
                        }
                        $i = $i + 1;
                    @endphp
                    <td class="{{ $cssClass }}">
                        {{ $differenceInDays }} Jours
                    </td>
                    <td>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ExtralargeModal1"
                            onclick='handlerechargerclick(@json($extincteur))'>Recharger
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="ExtralargeModal1" tabindex="-1"
        style="display: none; " aria-hidden="true" >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" >
                    <h5 class="modal-title" id="extincteur_name"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="row g-3 mx-auto" action="{{ route('app.securite.recharger_extincteur') }}" method="post">
                    @csrf
                    <input type="hidden" name="extincteur_id" id="extincteur_id">
                    <h5 style="font-family: 'Tajwal';margin-right:50px;" class="mt-4 ">Selectionner les dates de
                        rechargement et expiration:
                    </h5>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input name="date_expiration" type="date" required class="form-control" id="date_expiration" >
                            <label for="date_expiration">date de rechargement</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input name="date_rechargement" type="date" required class="form-control" id="date_rechargement">
                            <label for="date_rechargement">date d'éxpiration:</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function handlerechargerclick(taxi) {
            const modal_title = document.getElementById('extincteur_name');
            const confirm_id = document.getElementById('extincteur_id');

            modal_title.innerHTML = '';
            modal_title.innerHTML = 'Extinctuer: ' + taxi.affectation;
            confirm_id.value = taxi.id;
            confirm_name.innerHTML = '';
            confirm_name.innerHTML = ' ' + taxi.counter;
        }
    </script>
@endsection
