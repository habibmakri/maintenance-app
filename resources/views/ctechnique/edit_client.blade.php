@extends('base')
@section('title', 'modifier client')
@section('content')

    <div class="pagetitle">
        <h1>Modifier - client: {{ $client->name . ' - ' . $client->date_controle }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item active">Controle technique</li>
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
    <form action="" method="post">
        @csrf
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <select class="form-select" required name="type" required id="type" placeholder="type"
                        aria-label="Floating label select example">
                        @foreach ($clienttypes as $type)
                            <option value="{{ $type->id }}" @selected($client->type_id === $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <label for="type">Type</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $client->name }}" disabled id="floatingName"
                        name="name" placeholder="nom">
                    <label for="name">Nom-prénom</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $client->immatriculation }}" required
                        id="floatingName" name="immatriculation" placeholder="immatriculation">
                    <label for="name">Immatriculation</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control" value="{{ $client->phone }}" id="phone" id="floatingName"
                        name="phone"pattern="[0-9]{10}" placeholder="phone">
                    <label for="name">N° Téléphone</label>
                </div>
            </div>
        </div>
        <div class="text-end mt-2" >
            <button type="submit" class="btn btn-primary">Modifier</button>
        </div>
    </form>
@endsection
