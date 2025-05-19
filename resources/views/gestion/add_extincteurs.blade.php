@extends('base')
@section('title', 'Gestion des comptes')
@section('content')

    <div class="pagetitle">
        <h1>Gestion des Extincteurs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Gestion</li>
                <li class="breadcrumb-item active">Extincteurs</li>
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

        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" required id="floatingName" name="reference" placeholder="Reference" >
                <label for="reference">Reference</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-floating">
                <select class="form-select" required name="bus" required id="floatingName" placeholder="bus"
                    aria-label="Floating label select example">
                    <option value="" disabled selected>selectionner Bus/Terrain</option>
                    <option value="0">Terrain</option>
                    <option value="1">Bus</option>
                </select>
                <label for="bus">Bus/Terrain</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <select class="form-select" required name="type" required id="floatingName" placeholder="type"
                    aria-label="Floating label select example">
                    <option value="" disabled selected>selectionner Type</option>
                    <option value="CO2-02KG">CO2-02KG</option>
                    <option value="CO2-06KG">CO2-06KG</option>
                    <option value="Eau Paulverisee-09L">Eau Paulverisee-09L</option>
                    <option value="Poudre à GAS- PG 50KG - chariot">Poudre à GAS- PG 50KG "chariot"</option>
                    <option value="Poudre Seche-04KG">Poudre Seche-04KG</option>
                    <option value="Poudre Seche-06KG">Poudre Seche-06KG</option>
                    <option value="Poudre Seche-09KG">Poudre Seche-09KG</option>
                </select>
                <label for="type">Type</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" required id="floatingName" name="Affectation" placeholder="Affectation" >
                <label for="Affectation">Affectation</label>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-floating">
                <input name="date_recharge" type="date" required class="form-control" >
                <label for="date_recharge">Date Recharge</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input name="date_expiration" type="date" required class="form-control" >
                <label for="date_expiration">Date Expiration</label>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
    </form>
@endsection
