@extends('base')

@section('title', 'Clients cTechnique')

@section('content')
    <div class="pagetitle">
        <h1>Clients</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Controle technique</li>
                <li class="breadcrumb-item active">Clients</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">Controle proche</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Tous</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
            <h5 class="mt-2">Clients - 10 Jours</h5>

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
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Dernier controle</th>
                        <th>Téléphone</th>
                        <th>matricule</th>
                        <th>Fin controle</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        @if (abs(
                                \Carbon\Carbon::parse($client->date_controle)->addMonths((int) $client->type->mois)->diffInDays(now())) < 10. && \Carbon\Carbon::parse($client->last_remind)->diffInDays(\Carbon\Carbon::parse($client->date_controle)->addMonths((int) $client->type->mois)) > 10.)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->type->name }}</td>
                                <td>{{ $client->date_controle }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->immatriculation }}</td>
                                <td>{{ \Carbon\Carbon::parse($client->date_controle)->addMonths((int) $client->type->mois)->format('Y-m-d') }}
                                </td>
                                <td style="display: flex">
                                    <form action="{{route('app.ctechnique.sendmessage')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <i class="bi bi-chat-left-text" style="margin-right: 15%;"></i>
                                        </button>
                                    </form>
                                    <form action="{{route('app.ctechnique.refreshcontrole')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <i class="bi bi-calendar-date" style="margin-right: 15%;"></i>
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
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Dernier controle</th>
                        <th>Téléphone</th>
                        <th>matricule</th>
                        <th>Fin controle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->type->name }}</td>
                            <td>{{ $client->date_controle }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>{{ $client->immatriculation }}</td>
                            <td>{{ \Carbon\Carbon::parse($client->date_controle)->addMonths((int) $client->type->mois)->format('Y-m-d') }}
                            </td>
                            <td style="display: flex">
                                <i class="bi bi-trash delete-icon"  style="cursor: pointer;"
                                    onclick="delete_client({{ $client->id }})"></i>
                                    <i class="bi bi-pencil edit-icon"  style="margin-left:15%;cursor: pointer;" onclick="handleEditClick({{ $client->id }})"></i>
                            </td>
                        </tr>
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

        function delete_client(id) {
            console.log(id);
            if (confirm('Vous êtes sur?')) {
                fetch(`ctechnique/deleteclient:${id}`, {
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

        function handleEditClick(id) {
            console.log("Editing Client with ID:", id);
            window.location.href = `/app/ctechnique_clients/edit_client:${id}`;
        }

    </script>
@endsection
