@extends('base')
@section('title', 'Cartes gasoile')
@section('content')

    <div class="pagetitle">
        <h1>Cartes gasoile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Cartes gasoile</li>
            </ol>
        </nav>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ExtralargeModal3">Nouvelle Opération
        </button>
    </div>
    <div class="modal fade" id="ExtralargeModal3" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title3">Déclarer un nouveau Operation Gasoile:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('app.maintenance.maintenance_vidange') }}" method="post">
                        @csrf
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input name="date" type="date" required class="form-control">
                                <label for="date">Date</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" required name="brigade" id="brigade"
                                    aria-label="Floating label select example">
                                    <option value="" disabled selected>selectionner Carte</option>
                                    @foreach ($cartes as $carte )
                                        <option value="{{ $carte->id }}" >{{ $carte->name }}</option>
                                    @endforeach
                                </select>
                                <label for="brigade">Carte</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" required name="brigade" id="brigade"
                                    aria-label="Floating label select example">
                                    <option value="" disabled selected>selectionner chauffeur</option>
                                    @foreach ($agents as $agent )
                                        <option value="{{$agent->firstname.' '.$agent->lastname  }}" >{{ $agent->firstname.' '.$agent->lastname }}</option>
                                    @endforeach
                                </select>
                                <label for="brigade">Chauffeur</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" required step="any" class="form-control" name="gasoile" id="gasoile" value="0" min="0">
                                <label for="gasoile">Montant</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" required name="brigade" id="brigade"
                                    aria-label="Floating label select example">
                                    <option value="" disabled >selectionner Mission</option>
                                    <option value="oui">OUI</option>
                                    <option value="non" selected>NON</option>
                                </select>
                                <label for="brigade">Mission</label>
                            </div>
                        </div>
                       <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name" placeholder="Nom-prénom">
                                <label for="name">Lieu de recharge</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="description" style="height: 100px"></textarea>
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

    {{-- <table class="table datatable">
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
            @foreach ($vidanges as $vidange)
                <tr>
                    <td>{{ $vidange->id }}</td>
                    <td>{{ $vidange->date_resoudre }}</td>
                    <td>{{ $vidange->fichemaintenance->bus->name }}</td>
                    <td>{{ $vidange->brigade }}</td>
                    <td>{{ $vidange->pannename->name }}</td>
                    <td>
                        <i class="bi bi-trash edit-icon" data-id="{{ $vidange->id }}"
                            style="margin-right:15%;cursor: pointer;"
                            onclick="handleDeleteClick('{{ $vidange->id }}')"></i>
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


    </script>
@endsection
