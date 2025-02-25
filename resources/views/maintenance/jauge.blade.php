@extends('base')
@section('title', 'Jauge')
@section('content')

    <div class="pagetitle">
        <h1>Jauges</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Jauges</li>
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
    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">Huile moteur</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Glaciole</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="direction-tab" data-bs-toggle="tab" data-bs-target="#bordered-direction" type="button"
                role="tab" aria-controls="direction" aria-selected="false" tabindex="-1">Direction</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="btv-tab" data-bs-toggle="tab" data-bs-target="#bordered-btv" type="button"
                role="tab" aria-controls="btv" aria-selected="false" tabindex="-1">BTV</button>
        </li>
        {{-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="autre-tab" data-bs-toggle="tab" data-bs-target="#bordered-autre" type="button"
                role="tab" aria-controls="autre" aria-selected="false" tabindex="-1">Autre</button>
        </li> --}}
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
            <h3>Huile moteur</h3>
            <form class="row g-3 mb-3" action="{{ route('app.maintenance.ajouter_jauge_huilemoteur') }}" method="post">
                @csrf
                <div class="col-md-12">
                    <div class="form-floating">
                        <input name="date" id="datehuile" type="date" required="" class="form-control"
                            onchange="handleHuileDatechange()">
                        <label for="date">date</label>
                    </div>
                </div>
                @foreach ($buses as $bus)
                    <div class="col-md-2">
                        <div class="col-md-1 mb-3">
                            <div class="form" style="display: flex; gap:5px;">
                                <h2 style="margin: 0;">{{ $bus->name }}</h2>
                                <input name="{{ $bus->id }}" style="width: 76px;" type="number" step="any"
                                    min="0" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="select" name="equipe[]" id="equipe" multiple aria-label="equipe"
                            style="height: 120px;">
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->firstname }} {{ $agent->lastname }}">
                                    {{ $agent->firstname }}
                                    {{ $agent->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="form-select" required name="brigade" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled>selectionner brigade</option>
                            <option value="nuit" selected>Nuit</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigade">Brigade</label>
                    </div>
                </div>
                <button type="submit" disabled id="huilemoteursubmit"
                    class="btn btn-outline-primary col-md-12">Valider</button>
            </form>
        </div>
        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
            <h3>Glaciole</h3>
            <form class="row g-3 mb-3" action="{{ route('app.maintenance.ajouter_jauge_glaciole') }}" method="post">
                @csrf
                <div class="col-md-12">
                    <div class="form-floating">
                        <input name="date" id="dateglaciole" type="date" required="" class="form-control"
                            onchange="handleGlacioleDatechange()">
                        <label for="date">date</label>
                    </div>
                </div>
                @foreach ($buses as $bus)
                    <div class="col-md-2">
                        <div class="col-md-1 mb-3">
                            <div class="form" style="display: flex; gap:5px;">
                                <h2 style="margin: 0;">{{ $bus->name }}</h2>
                                <input name="{{ $bus->id }}" style="width: 76px;" type="number" step="any"
                                    min="0" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="select" name="equipe[]" id="equipe2" multiple aria-label="equipe"
                            style="height: 120px;">
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->firstname }} {{ $agent->lastname }}">
                                    {{ $agent->firstname }}
                                    {{ $agent->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="form-select" required name="brigade" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled>selectionner brigade</option>
                            <option value="nuit" selected>Nuit</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigade">Brigade</label>
                    </div>
                </div>
                <button type="submit" disabled id="glaciolesubmit"
                    class="btn btn-outline-primary col-md-12">Valider</button>
            </form>
        </div>
        <div class="tab-pane fade" id="bordered-direction" role="tabpanel" aria-labelledby="direction-tab">
            <h3>Direction</h3>
            <form class="row g-3 mb-3" action="{{ route('app.maintenance.ajouter_jauge_direction') }}" method="post">
                @csrf
                <div class="col-md-12">
                    <div class="form-floating">
                        <input name="date" id="datedirection" type="date" required="" class="form-control"
                            onchange="handleDirectionDatechange()">
                        <label for="date">date</label>
                    </div>
                </div>
                @foreach ($buses as $bus)
                    <div class="col-md-2">
                        <div class="col-md-1 mb-3">
                            <div class="form" style="display: flex; gap:5px;">
                                <h2 style="margin: 0;">{{ $bus->name }}</h2>
                                <input name="{{ $bus->id }}" style="width: 76px;" type="number" step="any"
                                    min="0" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="select" name="equipe[]" id="equipe4" multiple aria-label="equipe"
                            style="height: 120px;">
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->firstname }} {{ $agent->lastname }}">
                                    {{ $agent->firstname }}
                                    {{ $agent->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="form-select" required name="brigade" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled>selectionner brigade</option>
                            <option value="nuit" selected>Nuit</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigade">Brigade</label>
                    </div>
                </div>
                <button type="submit" disabled id="directionsubmit"
                    class="btn btn-outline-primary col-md-12">Valider</button>
            </form>
        </div>
        <div class="tab-pane fade" id="bordered-btv" role="tabpanel" aria-labelledby="btv-tab">
            <h3>BTV</h3>
            <form class="row g-3 mb-3" action="{{ route('app.maintenance.ajouter_jauge_btv') }}" method="post">
                @csrf
                <div class="col-md-12">
                    <div class="form-floating">
                        <input name="date" id="datebtv" type="date" required="" class="form-control"
                            onchange="handleBtvDatechange()">
                        <label for="date">date</label>
                    </div>
                </div>
                @foreach ($buses as $bus)
                    <div class="col-md-2">
                        <div class="col-md-1 mb-3">
                            <div class="form" style="display: flex; gap:5px;">
                                <h2 style="margin: 0;">{{ $bus->name }}</h2>
                                <input name="{{ $bus->id }}" style="width: 76px;" type="number" step="any"
                                    min="0" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="select" name="equipe[]" id="equipe5" multiple aria-label="equipe"
                            style="height: 120px;">
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->firstname }} {{ $agent->lastname }}">
                                    {{ $agent->firstname }}
                                    {{ $agent->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="form-select" required name="brigade" id="brigade"
                            aria-label="Floating label select example">
                            <option value="" disabled>selectionner brigade</option>
                            <option value="nuit" selected>Nuit</option>
                            <option value="matin">Matin</option>
                            <option value="soir">Soir</option>
                        </select>
                        <label for="brigade">Brigade</label>
                    </div>
                </div>
                <button type="submit" disabled id="btvsubmit"
                    class="btn btn-outline-primary col-md-12">Valider</button>
            </form>
        </div>
        <div class="tab-pane fade" id="bordered-autre" role="tabpanel" aria-labelledby="autre-tab">
            <h3>Autre</h3>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('app.maintenance.maintenance_jauge') }}" method="post">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input name="date" type="date" required class="form-control">
                            <label for="date">Date</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" name="bus" id="bus" required
                                aria-label="Floating label select example">
                                <option value="" disabled selected>selectionner Bus</option>
                                @foreach ($buses as $bus)
                                    <option value="{{ $bus->id }}" data-kmactuelle="{{ $bus->kmactuelle }}">
                                        {{ $bus->name }}</option>
                                @endforeach
                            </select>
                            <label for="bus">BUS</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" required name="brigade" id="brigade"
                                aria-label="Floating label select example">
                                <option value="" disabled>selectionner brigade</option>
                                <option value="nuit" selected>Nuit</option>
                                <option value="matin">Matin</option>
                                <option value="soir">Soir</option>
                            </select>
                            <label for="brigade">Brigade</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <select class="form-select" required name="nomvidange" id="nomvidange"
                                aria-label="Floating label select example">
                                <option value="" disabled selected>selectionner type</option>
                                @foreach ($typejauges as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }}</option>
                                @endforeach
                            </select>
                            <label for="nomvidange">Type</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating">
                            <select class="select" name="equipe[]" id="equipe7" required multiple aria-label="equipe"
                                style="height: 120px;">
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->firstname }} {{ $agent->lastname }}">
                                        {{ $agent->firstname }}
                                        {{ $agent->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <p class="mt-4">Pieces</p>
                    <div class="col-md-8" id="pieces-section">
                        <div class="form-floating">
                            <select class="form-select" name="pieces[]">
                                <option value="" disabled selected>Séléctionner la piece</option>
                                @foreach ($pieces as $piece)
                                    <option value="{{ $piece->id }}">{{ $piece->name }}</option>
                                @endforeach
                            </select>
                            <label for="pieces">Piece</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="number" class="form-control" step="any" name="piece_quantities[]"
                                min="0">
                            <label for="piece_quantities">Quantité</label>
                        </div>
                    </div>
                    {{-- <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" name="description" style="height: 100px"></textarea>
                            <label for="description">Déscription</label>
                        </div>
                    </div> --}}
                    <button type="submit" class="btn btn-primary">Valider</button>
                </form>
            </div>
        </div>
    </div>

    {{--     
    <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ExtralargeModal3">Nouvelle
            Jauge
        </button>
    </div>
    <div class="modal fade" id="ExtralargeModal3" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title3">Déclarer une nouvelle Jauge:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('app.maintenance.maintenance_jauge') }}" method="post">
                        @csrf
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input name="date" type="date" required class="form-control">
                                <label for="date">Date</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" name="bus" id="bus" required
                                    aria-label="Floating label select example">
                                    <option value="" disabled selected>selectionner Bus</option>
                                    @foreach ($buses as $bus)
                                        <option value="{{ $bus->id }}" data-kmactuelle="{{ $bus->kmactuelle }}">
                                            {{ $bus->name }}</option>
                                    @endforeach
                                </select>
                                <label for="bus">BUS</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" required name="brigade" id="brigade"
                                    aria-label="Floating label select example">
                                    <option value="" disabled >selectionner brigade</option>
                                    <option value="nuit" selected>Nuit</option>
                                    <option value="matin">Matin</option>
                                    <option value="soir">Soir</option>
                                </select>
                                <label for="brigade">Brigade</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <select class="form-select" required name="nomvidange" id="nomvidange"
                                    aria-label="Floating label select example">
                                    <option value="" disabled selected>selectionner type</option>
                                    @foreach ($typejauges as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <label for="nomvidange">Type</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <select class="select" name="equipe[]" id="equipe" required multiple aria-label="equipe"
                                    style="height: 120px;">
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->firstname }} {{ $agent->lastname }}">
                                            {{ $agent->firstname }}
                                            {{ $agent->lastname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p class="mt-4">Pieces</p>
                        <div class="col-md-8" id="pieces-section">
                            <div class="form-floating">
                                <select class="form-select" name="pieces[]" >
                                    <option value="" disabled selected>Séléctionner la piece</option>
                                    @foreach ($pieces as $piece)
                                    <option value="{{ $piece->id }}">{{ $piece->name }}</option>
                                    @endforeach
                                </select>
                                <label for="pieces">Piece</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" step="any" name="piece_quantities[]" min="1" >
                                <label for="piece_quantities">Quantité</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="description" style="height: 100px" ></textarea>
                                <label for="description">Déscription</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}


    {{-- @if (session('success'))
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
                    BUS
                </th>
                <th>Date</th>
                <th>Brigade</th>
                <th>type</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jauges as $jauge)
                <tr>
                    <td>{{ $jauge->id }}</td>
                    <td>{{ $jauge->date_resoudre }}</td>
                    <td>{{ $jauge->fichemaintenance->bus->name }}</td>
                    <td>{{ $jauge->brigade }}</td>
                    <td>{{ $jauge->pannename->name }}</td>
                    <td>
                        <i class="bi bi-trash edit-icon" data-id="{{ $jauge->id }}"
                            style="margin-right:15%;cursor: pointer;"
                            onclick="handleDeleteClick('{{ $jauge->id }}')"></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
    <script>
        function handleDeleteClick(id) {
            if (confirm('Vous êtes sur?')) {
                fetch(`manage_panne/deletepannesd:${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // alert('Operation Reussit!');
                            window.location.reload();
                        } else {
                            alert('Operation echoué!');
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectIds = ['equipe', 'equipe2','equipe4', 'equipe5'];
            selectIds.forEach((id) => {
                new TomSelect(`#${id}`, {
                    plugins: ['remove_button'],
                    create: false,
                    maxItems: null,
                    placeholder: 'Equipe',
                    searchField: ['text'],
                });
            });
        });

        function handleHuileDatechange() {
            const selectedDate = document.getElementById('datehuile').value;
            const huilemoteursubmit = document.getElementById('huilemoteursubmit');

            if (!selectedDate) return;
            console.log(selectedDate);
            fetch(`maintenance/check_jauge_date?date=${selectedDate}&type=${'huilemoteur'}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.exists);
                    if (data.exists === false) {
                        huilemoteursubmit.disabled = false
                    } else {
                        huilemoteursubmit.disabled = true
                        alert('Date deja remplie');
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }

        function handleGlacioleDatechange() {
            const selectedDate = document.getElementById('dateglaciole').value;
            const glaciolesubmit = document.getElementById('glaciolesubmit');

            if (!selectedDate) return;
            console.log(selectedDate);
            fetch(`maintenance/check_jauge_date?date=${selectedDate}&type=${'glaciole'}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.exists);
                    if (data.exists === false) {
                        glaciolesubmit.disabled = false
                    } else {
                        glaciolesubmit.disabled = true
                        alert('Date deja remplie');
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
        function handleDirectionDatechange() {
            const selectedDate = document.getElementById('datedirection').value;
            const directionsubmit = document.getElementById('directionsubmit');

            if (!selectedDate) return;
            console.log(selectedDate);
            fetch(`maintenance/check_jauge_date?date=${selectedDate}&type=${'direction'}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.exists);
                    if (data.exists === false) {
                        directionsubmit.disabled = false
                    } else {
                        directionsubmit.disabled = true
                        alert('Date deja remplie');
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
        function handleBtvDatechange() {
            const selectedDate = document.getElementById('datebtv').value;
            const btvsubmit = document.getElementById('btvsubmit');

            if (!selectedDate) return;
            console.log(selectedDate);
            fetch(`maintenance/check_jauge_date?date=${selectedDate}&type=${'btv'}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.exists);
                    if (data.exists === false) {
                        btvsubmit.disabled = false
                    } else {
                        btvsubmit.disabled = true
                        alert('Date deja remplie');
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
    </script>
@endsection
