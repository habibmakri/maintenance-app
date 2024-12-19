@extends('base')
@section('title', 'Gestion des comptes')
@section('content')

    <div class="pagetitle">
        <h1>Gestion des Buses</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Gestion</li>
                <li class="breadcrumb-item active">buses</li>
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
                <input type="text" class="form-control" required id="floatingName" name="name" placeholder="nom" >
                <label for="name">Nom</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="number" class="form-control" required id="floatingName" name="kmactuelle" placeholder="prénom" >
                <label for="kmactuelle">KMactuelle</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="number" class="form-control" required id="floatingName" name="derniervidange" placeholder="prénom" >
                <label for="derniervidange">Dernier vidange</label>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
        <div id="bus-form-container" class="row"></div>
    </form>
@endsection
