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
                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="description" style="height: 100px" >Remplissage</textarea>
                                <label for="description">Déscription</label>
                            </div>
                        </div>
                        <p class="mt-4">Pieces</p>
                        <div class="col-md-8" id="pieces-section">
                            <div class="form-floating">
                                <select class="form-select" name="pieces[]" required>
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
                                <input type="number" class="form-control" step="any" name="piece_quantities[]" min="1" required>
                                <label for="piece_quantities">Quantité</label>
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
    </table>
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
            const selectIds = ['equipe'];
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

        const busSelect = document.getElementById('bus');
        busSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            kilometrage = document.getElementById('kilometrage');
            kilometrage.value = selectedOption.dataset.kmactuelle;
        });
    </script>
@endsection
