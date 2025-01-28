@extends('base')

@section('title', 'Pannes')

@section('content')
    <div class="pagetitle">
        <h1>Gestion des pannes</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Pannes</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">En-cours</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Résolue</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
            <h5 class="mt-2">Liste des Pannes en cours</h5>

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
            <div class="text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#ExtralargeModal3">Déclarer une panne
                </button>
            </div>
            <div class="modal fade" id="ExtralargeModal3" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_title3">Déclarer une nouvelle panne:</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" action="{{ route('app.maintenance.ajouter_ndpanne') }}" method="post">
                                @csrf
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input name="date" type="date" required class="form-control">
                                        <label for="date">Date</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" name="bus" id="bus" required
                                            aria-label="Floating label select example">
                                            @foreach ($buses as $bus)
                                                <option value="{{ $bus->id }}">{{ $bus->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="bus">BUS</label>
                                    </div>
                                </div>
                                <h4>Pannes:</h4>
                                <div class="col-md-4">
                                    <div class="form-check form-switch" style="padding-left: 0em;">
                                        <label class="form-check-label" for="togglePanneMecanique">Pannes mécanique:</label>
                                        <input class="form-check-input" type="checkbox" name="pannemecaniquecheck"
                                            id="togglePanneMecanique" style="float: none; margin-left: 0.5em"
                                            onchange="toggleSelect('panneMecanique')">
                                        <select class="select" disabled name="pannemecanique[]" id="panneMecanique" multiple
                                            aria-label="autorisations" style="height: 100px;">
                                            @foreach ($pannenames->where('type', 'mecanique') as $panne)
                                                <option value="{{ $panne->id }}">{{ $panne->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch" style="padding-left: 0em;">
                                        <label class="form-check-label" for="togglePanneElectrique">Pannes
                                            éléctrique:</label>
                                        <input class="form-check-input" type="checkbox" name="panneelectriquecheck"
                                            id="togglePanneElectrique" style="float: none; margin-left: 0.5em"
                                            onchange="toggleSelect('panneElectrique')">
                                        <select class="select" disabled name="panneelectrique[]" id="panneElectrique"
                                            multiple aria-label="autorisations" style="height: 100px;">
                                            @foreach ($pannenames->where('type', 'electrique') as $panne)
                                                <option value="{{ $panne->id }}">{{ $panne->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch" style="padding-left: 0em;">
                                        <label class="form-check-label" for="togglePanneTolle">Pannes de Tolles:</label>
                                        <input class="form-check-input" type="checkbox" name="pannetollecheck"
                                            id="togglePanneTolle" style="float: none; margin-left: 0.5em"
                                            onchange="toggleSelect('panneTolle')">
                                        <select class="select" disabled name="pannetolle[]" id="panneTolle" multiple
                                            aria-label="autorisations" style="height: 100px;">
                                            @foreach ($pannenames->where('type', 'tolle') as $panne)
                                                <option value="{{ $panne->id }}">{{ $panne->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
                        <th>Bus</th>
                        <th data-type="date" data-format="YYYY/DD/MM">Date</th>
                        <th>Brigade</th>
                        <th>Pannes</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pannes as $panne)
                        <tr
                            @if ($panne->fichemaintenance->brigade) style="border-color: green;" @else style="border-color: red;" @endif>
                            <td>{{ $panne->id }}</td>
                            <td>{{ $panne->fichemaintenance->bus->name }}</td>
                            <td>{{ $panne->fichemaintenance->date_fiche }}</td>
                            <td>{{ $panne->fichemaintenance->brigade }}</td>
                            <td>{{ $panne->pannename->name }}</td>
                            <td>{{ $panne->pannename->type }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal1"
                                    onclick="handleresoudreclick({{ $panne }})">Résoudre</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="modal fade" id="ExtralargeModal1" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_title"> </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" action="" method="post">
                                @csrf
                                <input type="hidden" name="fichepanne_id" id="fichepanne_id">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input name="dateresolu" type="date" required class="form-control">
                                        <label for="dateresolu">Date</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" required name="lieuresolu" id="lieuresolu"
                                            aria-label="Floating label select example">
                                            <option value="" disabled selected>selectionner Lieu</option>
                                            @foreach ($stations as $station)
                                                <option value="{{ $station->name }}">{{ $station->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="lieuresolu">Lieu</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" required name="brigade" id="brigade"
                                            aria-label="Floating label select example">
                                            <option value="" disabled selected>selectionner brigade</option>
                                            <option value="matin">Matin</option>
                                            <option value="soir">Soir</option>
                                            <option value="nuit">Nuit</option>
                                        </select>
                                        <label for="brigade">Brigade</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <select class="select" name="equipe[]" id="equipe" multiple
                                            aria-label="equipe" style="height: 120px;">
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->firstname }} {{ $agent->lastname }}">
                                                    {{ $agent->firstname }}
                                                    {{ $agent->lastname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <p class="mt-4">Pieces</p>
                                <div id="pieces-section">

                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-secondary btn-sm" id="add-piece">
                                        Ajouter pieces
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="description" style="height: 100px"></textarea>
                                        <label for="description">Déscription</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Valider</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
            <h5 class="mt-2">Selectionner la date :</h5>
            <table class="table datatable mt-1">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Bus</th>
                        <th data-type="date" data-format="YYYY/DD/MM">Date</th>
                        <th>Brigade</th>
                        <th>Pannes</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pannesresolue as $panne)
                        <tr>
                            <td>{{ $panne->id }}</td>
                            <td>{{ $panne->fichemaintenance->bus->name }}</td>
                            <td>{{ $panne->fichemaintenance->date_fiche }}</td>
                            <td>{{ $panne->fichemaintenance->brigade }}</td>
                            <td>{{ $panne->pannename->name }}</td>
                            <td>{{ $panne->pannename->type }}</td>
                            <td>
                                {{-- <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#ExtralargeModal2"
                                onclick="handlerapportclick({{ $panne}},{{$panne->used_pieces[0]->piece }})"> Rapport</button> --}}
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal2"
                                    onclick="handlerapportclick({{ json_encode($panne) }}, {{ json_encode($panne->used_pieces->map(function ($piece) {return ['name' => $piece->piece->name, 'quantity' => $piece->quantité];})->toArray()) }},{{ json_encode($panne->fichemaintenance->chauffeur) }})">Rapport</button>
                                <i class="bi bi-trash delete-icon" style="margin-left:15%;cursor: pointer;"
                                    onclick="handleDeleteClick({{ $panne->id }})"></i>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="modal fade" id="ExtralargeModal2" tabindex="-1" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_title2"> </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <p id="raport_bus"></p>
                                </div>
                                <div class="col-md-4">
                                    <p id="raport_panne"></p>
                                </div>
                                <div class="col-md-4">
                                    <p id="raport_panne_type"></p>
                                </div>
                                <div class="col-md-4">
                                    <p>Etat: Résolue</p>
                                </div>
                                <div class="col-md-4">
                                    <p id="raport_declarepar"> </p>
                                </div>
                                <div class="col-md-4">
                                    <p id="raport_datedeclaration"> </p>
                                </div>
                                <div class="col-md-4">
                                    <p id="raport_dateresolution"> </p>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <p id="raport_equipe"></p>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <p id="raport_description"></p>
                                </div>
                                <div class="col-md-12">
                                    <p id="raport_pieces">Pieces utulisé:</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <form action="{{ route('app.maintenance.panne_pdf') }}" method="post">
                                @csrf
                                <input id="id_raport_bus" name='fiche_id' required type="hidden">
                                <button type="submit" class="btn btn-success">imprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="bordered-contact" role="tabpanel" aria-labelledby="contact-tab">
            <h5 class="mt-2">Selectionner la date:</h5>


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
        function handleDeleteClick(id) {
            if (confirm('Vous êtes sur?')) {
                fetch(`maintenance/deletefichepanne:${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Opération réussie!');
                            location.reload(); 
                        } else {
                            alert('Opération échouée!');
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        }

        function handlerapportclick(panne, used_pieces, chauffeur) {
            const modal_title = document.getElementById('modal_title2');
            const raport_bus = document.getElementById('raport_bus');
            const raport_panne = document.getElementById('raport_panne');
            const raport_declarepar = document.getElementById('raport_declarepar');
            const raport_datedeclaration = document.getElementById('raport_datedeclaration');
            const raport_dateresolution = document.getElementById('raport_dateresolution');
            const raport_brigadedeclaration = document.getElementById('raport_brigadedeclaration');
            const raport_brigaderesolution = document.getElementById('raport_brigaderesolution');
            const raport_equipe = document.getElementById('raport_equipe');
            const raport_description = document.getElementById('raport_description');
            const raport_pieces = document.getElementById('raport_pieces');
            const id_raport_bus = document.getElementById('id_raport_bus');
            const raport_panne_type = document.getElementById('raport_panne_type');
            modal_title.innerHTML = '';
            raport_bus.innerHTML = '';
            raport_panne.innerHTML = '';
            raport_panne_type.innerHTML = '';
            raport_declarepar.innerHTML = '';
            raport_datedeclaration.innerHTML = '';
            raport_dateresolution.innerHTML = '';
            raport_equipe.innerHTML = '';
            raport_description.innerHTML = '';
            raport_pieces.innerHTML = '';
            id_raport_bus.value = '';
            modal_title.innerHTML = 'Rapport du Panne: ' + panne.pannename.name + ' du ' + panne.fichemaintenance.bus.name +
                ' signaler le ' +
                panne.fichemaintenance.date_fiche + ' - ' + panne.fichemaintenance.brigade;
            raport_bus.innerHTML = 'Bus: ' + panne.fichemaintenance.bus.name;
            raport_panne.innerHTML = 'Panne: ' + panne.pannename.name;
            raport_panne_type.innerHTML = 'Type: ' + panne.pannename.type;
            if (chauffeur && chauffeur.fr_name) {
                raport_declarepar.innerHTML = 'Déclaré par: ' + chauffeur.fr_name;
            } else {
                raport_declarepar.innerHTML = 'Déclaré par: Équipe Maintenance';
            }
            raport_datedeclaration.innerHTML = 'Déclaré le: ' + panne.fichemaintenance.date_fiche + '-' + panne
                .fichemaintenance.brigade;
            raport_dateresolution.innerHTML = 'Résolue le: ' + panne.date_resoudre + '-' + panne.brigade;
            raport_equipe.innerHTML = 'Équipe intervenue:<br> ' + JSON.parse(panne.equipe).join(', ');
            raport_description.innerHTML = 'Description:<br> ' + panne.description;
            const piecesDetails = used_pieces.map(piece => `${piece.name} => Quantié: ${piece.quantity}`).join('  ||  ');
            raport_pieces.innerHTML = 'Pièces utilisées:<br>' + piecesDetails;
            id_raport_bus.value = panne.id;
            console.log(panne.id);
        }
        document.getElementById('add-piece').addEventListener('click', function() {
            const toolsSection = document.getElementById('pieces-section');
            const toolDiv = document.createElement('div');
            toolDiv.classList.add('row', 'g-3', 'mb-3', 'align-items-center');
            const selectDiv = document.createElement('div');
            selectDiv.classList.add('col-md-6');
            selectDiv.innerHTML = `
            <div class="form-floating">
                <select class="form-select" name="pieces[]" required>
                    <option value="" disabled selected>Séléctionner la piece</option>
                    @foreach ($pieces as $piece)
                        <option value="{{ $piece->id }}">{{ $piece->name }}</option>
                    @endforeach
                </select>
                <label for="pieces">Piece</label>
            </div>
        `;
            const numberDiv = document.createElement('div');
            numberDiv.classList.add('col-md-5');
            numberDiv.innerHTML = `
            <div class="form-floating">
                <input type="number" class="form-control" name="piece_quantities[]" min="1" required>
                <label for="piece_quantities">Quantité</label>
            </div>
        `;
            const deleteDiv = document.createElement('div');
            deleteDiv.classList.add('col-md-1', 'text-center');
            deleteDiv.innerHTML = `
            <button type="button" class="btn btn-danger btn-sm remove-piece">Annuler</button>
        `;
            toolDiv.appendChild(selectDiv);
            toolDiv.appendChild(numberDiv);
            toolDiv.appendChild(deleteDiv);
            toolsSection.appendChild(toolDiv);
            deleteDiv.querySelector('.remove-piece').addEventListener('click', function() {
                toolDiv.remove();
            });
        });

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

        function toggleSelect(selectId) {
            const selectElement = document.getElementById(selectId); // Corrected to use `getElementById`
            const partitSelect = document.getElementById('partit');
            const id_chauffer = document.getElementById('id_chauffeur');
            if (selectElement.tomselect) {
                if (selectElement.disabled) {
                    selectElement.tomselect.enable(); // Enable Tom Select
                } else {
                    selectElement.tomselect.disable(); // Disable Tom Select
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Tom Select for each <select> element
            const selectIds = ['panneMecanique', 'panneElectrique', 'panneTolle'];
            selectIds.forEach((id) => {
                new TomSelect(`#${id}`, {
                    plugins: ['remove_button'], // Enables the remove button for selected items
                    create: false, // Prevents users from adding custom options
                    maxItems: null, // Allows multiple selection
                    placeholder: 'Selectionner', // Placeholder text
                    searchField: ['text'], // Enables searching in the options
                });
            });
        });
    </script>
@endsection
