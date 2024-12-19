@extends('base')
@section('title', 'Gestion des comptes')
@section('content')

    <div class="pagetitle">
        <h1>Gestion des comptes des utilisateurs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Gestion</li>
                <li class="breadcrumb-item ">Comptes</li>
                <li class="breadcrumb-item active">Modifier</li>
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
    <form class="row g-3" action="" method="post">
        @csrf

        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control" required id="floatingName" name="firstname" placeholder="nom" value="{{$record->firstname}}">
                <label for="firstname">Nom</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control" required id="floatingName" name="lastname" placeholder="prénom" value="{{$record->lastname}}">
                <label for="lastname">Prénom</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="tel" class="form-control" id="floatingName" required name="telephone" pattern="[0-9]{10}"
                    placeholder="telephone" value="{{$record->telephone}}">
                <label for="telephone">Téléphone</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingName" required name="poste" placeholder="poste" value="{{$record->poste}}">
                <label for="poste">Poste</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <select class="form-select" required name="service" id="service" placeholder="Service"
                    aria-label="Floating label select example">
                    <option value="maintenance" @selected($record->service === 'maintenance')>Maintenance</option>
                    <option value="exploatation" @selected($record->service === 'exploatation')>Exploatation</option>
                    <option value="archive" @selected($record->service === 'archive')>Archive</option>
                    <option value="magasin" @selected($record->service === 'magasin')>Magasin</option>
                    <option value="securité" @selected($record->service === 'securité')>Securité</option>
                </select>
                <label for="service">Service</label>
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-floating">
                <input type="email" class="form-control" required id="floatingName" name="email" placeholder="email" value="{{$record->email}}">
                <label for="email">E-mail</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" class="form-control" required id="floatingName" name="password"
                    placeholder="password">
                <label for="password">Mot de passe</label>
            </div>
        </div> --}}
        <div class="col-md-12">
            <div class="form-floating">
                <select class="form-select" name="autorisations[]" multiple aria-label="autorisations" style="height:125px;">
                    <option value="maintenance_in" {{ in_array('maintenance_in', $record->autorisations ?? []) ? 'selected' : '' }}>Insertion données maintenance</option>
                    <option value="maintenance_fix" {{ in_array('maintenance_fix', $record->autorisations ?? []) ? 'selected' : '' }}>Modification données maintenance</option>
                    <option value="maintenance_out" {{ in_array('maintenance_out', $record->autorisations ?? []) ? 'selected' : '' }}>Extraction données maintenance</option>
                    <option value="manage_user" {{ in_array('manage_user', $record->autorisations ?? []) ? 'selected' : '' }}>Gestion des comptes</option>
                    <option value="manage_ligne" {{ in_array('manage_ligne', $record->autorisations ?? []) ? 'selected' : '' }}>Gestion des Lignes</option>
                    <option value="manage_bus" {{ in_array('manage_bus', $record->autorisations ?? []) ? 'selected' : '' }}>Gestion des Bus</option>
                    <option value="manage_panne" {{ in_array('manage_panne', $record->autorisations ?? []) ? 'selected' : '' }}>Gestion des Pannes</option>

                </select>
                <label for="autorisations">Permissions</label>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Modifier</button>
        </div>
        <div id="bus-form-container" class="row"></div>
    </form>
@endsection
