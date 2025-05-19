@extends('base')
@section('title', 'Extincteurs')
@section('content')

    <div class="pagetitle">
        <h1>Extincteurs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Gestion</li>
                <li class="breadcrumb-item active">Extincteurs</li>
            </ol>
        </nav>
        <div class="text-end">
            <a href="{{ route('app.gestion.add_extincteur') }}" class="btn btn-primary">
                Nouveau Extincteur
            </a>

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
                    Reference
                </th>
                <th>Type</th>
                <th>Affectation</th>
                <th>Date Recahrge</th>
                <th>Date d'expiration</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($extincteurs as $extincteur)
                <tr>
                    <td>{{ $extincteur->id }}</td>
                    <td>{{ $extincteur->reference }}</td>
                    <td>{{ $extincteur->type }}</td>
                    <td>{{ $extincteur->affectation }}</td>
                    <td>{{ $extincteur->date_recharge }}</td>
                    <td>{{ $extincteur->date_expiration }}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#ExtralargeModal"
                            onclick="handlerechargeclick({{ $extincteur }})">Recharger</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="ExtralargeModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" >
                    <h5 class="modal-title" id="modal_title"> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form class="row g-3" action="{{ route('app.gestion.recharge_extincteur') }}" method="post">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input name="date_recharge" type="date" required class="form-control">
                                <label for="date_recharge">Date Recharge</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input name="date_expiration" type="date" required class="form-control">
                                <label for="date_expiration">Date Expiration</label>
                            </div>
                        </div>

                        <input type="hidden" name="extincteurid" id="extincteurid">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        function handleEditClick(id) {
            console.log("Editing bus with ID:", id);
            window.location.href = `/app/manage_bus/edit_bus:${id}`;
        }
        function handlerechargeclick(extincteur) {
            const modal_title = document.getElementById('modal_title');
            const declarationIdInput = document.getElementById('extincteurid');
            modal_title.innerHTML = '';
            modal_title.innerHTML = extincteur.reference + ' - ' + extincteur.affectation;
            declarationIdInput.value = extincteur.id; 
        }

    </script>
@endsection
