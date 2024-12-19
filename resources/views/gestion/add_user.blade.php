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
                <li class="breadcrumb-item active">Ajouter</li>
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
                <input type="text" class="form-control" required id="floatingName" name="firstname" placeholder="nom">
                <label for="firstname">Nom</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control" required id="floatingName" name="lastname" placeholder="prénom">
                <label for="lastname">Prénom</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="tel" class="form-control" id="floatingName" required name="telephone" pattern="[0-9]{10}"
                    placeholder="telephone">
                <label for="telephone">Téléphone</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingName" required name="poste" placeholder="poste">
                <label for="poste">Poste</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <select class="form-select" required name="service" required id="service" placeholder="Service"
                    aria-label="Floating label select example">
                    <option value="" disabled selected>selectionner service</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="exploatation">Exploatation</option>
                    <option value="archive">Archive</option>
                    <option value="magasin">Magasin</option>
                    <option value="securité">Securité</option>
                </select>
                <label for="service">Service</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="email" class="form-control" required id="floatingName" name="email" placeholder="email">
                <label for="email">E-mail</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" class="form-control" required id="floatingName" name="password"
                    placeholder="password">
                <label for="password">Mot de passe</label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-floating">
                <select class="form-select" name="autorisations[]" multiple aria-label="autorisations" style="height:125px;">
                    <option value="maintenance_in">Insertion données maintenance</option>
                    <option value="maintenance_fix">Modification données maintenance</option>
                    <option value="maintenance_out">Extraction données maintenance</option>
                    <option value="manage_user">Gestion des comptes</option>
                    <option value="manage_ligne">Gestion des Lignes</option>
                    <option value="manage_bus">Gestion des Bus</option>
                    <option value="manage_panne">Gestion des Pannes</option>
                </select>
                <label for="autorisations">Permisions</label>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
        <div id="bus-form-container" class="row"></div>
    </form>
@endsection
