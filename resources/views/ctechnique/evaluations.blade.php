@extends('base')

@section('title', 'Evaluations')

@section('content')
    <div class="pagetitle">
        <h1>Evaluations</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Controle technique</li>
                <li class="breadcrumb-item active">Evaluations</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">Nouvelle</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Lue</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
            <h5 class="mt-2">Nouvelles Evaluations</h5>

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

            <table class="table datatable mt-1">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Service</th>
                        <th>Controlleur</th>
                        <th>propreté</th>
                        <th>gestion</th>
                        <th>message</th>
                        <th>N°Telephone</th>
                        <th>Date</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ratings as $rating)
                        @if (!$rating->read)
                            <tr>
                                <td>{{ $rating->id }}</td>
                                <td
                                    @if ($rating->service === 'mauvais') style="border-color: red;" @elseif ($rating->service === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    {{ $rating->service }}</td>
                                <td
                                    @if ($rating->controler === 'mauvais') style="border-color: red;" @elseif ($rating->controler === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    {{ $rating->controler }}</td>
                                <td
                                    @if ($rating->clean === 'mauvais') style="border-color: red;" @elseif ($rating->clean === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    {{ $rating->clean }}</td>
                                <td
                                    @if ($rating->order === 'mauvais') style="border-color: red;" @elseif ($rating->order === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    {{ $rating->order }}</td>
                                <td>{{ $rating->message }}</td>
                                <td>{{ $rating->phone }}</td>
                                <td>{{ $rating->created_at }}</td>
                                <td style="display: flex">
                                    <form action="{{ route('app.ctechnique.marquercommelue') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rating_id" value="{{ $rating->id }}">
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <i class="bi bi-eye-fill" style="margin-right: 15%;"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('app.ctechnique.print_evaluation') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rating_id" value="{{ $rating->id }}">
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <i class="bi bi-printer" style="margin-right: 15%;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
            <h5 class="mt-2">Selectionner la date :</h5>
            <table class="table datatable mt-1">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Service</th>
                        <th>Controlleur</th>
                        <th>propreté</th>
                        <th>gestion</th>
                        <th>message</th>
                        <th>N°Telephone</th>
                        <th data-type="date" data-format="YYYY/DD/MM">Date</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ratings as $rating)
                        @if ($rating->read)
                            <tr>
                                <td>{{ $rating->id }}</td>
                                <td
                                    @if ($rating->service === 'mauvais') style="border-color: red;" @elseif ($rating->service === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    {{ $rating->service }}</td>
                                <td
                                    @if ($rating->controler === 'mauvais') style="border-color: red;" @elseif ($rating->controler === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    {{ $rating->controler }}</td>
                                <td
                                    @if ($rating->clean === 'mauvais') style="border-color: red;" @elseif ($rating->clean === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    {{ $rating->clean }}</td>
                                <td
                                    @if ($rating->order === 'mauvais') style="border-color: red;" @elseif ($rating->order === 'moyen') style="border-color: yellow;" @else style="border-color: green;" @endif>
                                    {{ $rating->order }}</td>
                                <td>{{ $rating->message }}</td>
                                <td>{{ $rating->phone }}</td>
                                <td>{{ $rating->created_at }}</td>
                                <td>
                                    <form action="{{ route('app.ctechnique.print_evaluation') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="rating_id" value="{{ $rating->id }}">
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <i class="bi bi-printer" style="margin-right: 15%;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

        </div>


    </div>


    <script>
        function handleresoudreclick(panne) {
            const modal_title = document.getElementById('modal_title');
            modal_title.innerHTML = panne.pannename.name + ' du ' + panne.fichemaintenance.bus.name + ' signaler le ' +
                panne.fichemaintenance.date_fiche + ' - ' + panne.fichemaintenance.brigade;
            const panneIdInput = document.getElementById('fichepanne_id');
            panneIdInput.value = panne.id;
        }
    </script>
@endsection
