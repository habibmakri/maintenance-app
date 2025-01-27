@extends('base')
@section('title', 'Clients cTechnique')
@section('content')

    <div class="pagetitle">
        <h1>Saisir des clients</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item active">Controle technique</li>
                <li class="breadcrumb-item active">Saisir</li>
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


    <form class="row g-3" action="{{ route('app.ctechnique.ctechnique_in') }}" method="post">
        @csrf

        <div class="col-md-12">
            <div class="form-floating">
                <input type="date" class="form-control" required id="floatingName" name="date" placeholder="date">
                <label for="date">Date</label>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating">
                    <select class="form-select" required name="type[]" required id="type" placeholder="type"
                        aria-label="Floating label select example">
                        @foreach ($clienttypes as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                    <label for="type">Type</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="text" class="form-control" required id="floatingName" name="name[]" placeholder="nom">
                    <label for="name">Nom-prénom</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control" required id="floatingName" name="immatriculation[]"
                        placeholder="immatriculation">
                    <label for="name">Immatriculation</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="phone" id="floatingName" name="phone[]"pattern="[0-9]{10}"
                        placeholder="phone">
                    <label for="name">N° Téléphone</label>
                </div>
            </div>
        </div>

        <div id="client-container">
            <div class="client-form row g-3">
                <div class="col-md-2">
                    <div class="form-floating">
                        <select class="form-select" name="type[]" id="type" placeholder="type"
                            aria-label="Floating label select example">
                            @foreach ($clienttypes as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                        <label for="type">Type</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="name[]" placeholder="Nom-prénom">
                        <label for="name">Nom-prénom</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="immatriculation[]" placeholder="Immatriculation">
                        <label for="immatriculation">Immatriculation</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="phone" name="phone[]"pattern="[0-9]{10}" placeholder="N° Téléphone">
                        <label for="phone">N° Téléphone</label>
                    </div>
                </div>
            </div>
        </div>
        <div id="client-container">
            <div class="client-form row g-3">
                <div class="col-md-2">
                    <div class="form-floating">
                        <select class="form-select" name="type[]" id="type" placeholder="type"
                            aria-label="Floating label select example">
                            @foreach ($clienttypes as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                        <label for="type">Type</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="name[]" placeholder="Nom-prénom">
                        <label for="name">Nom-prénom</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="immatriculation[]"
                            placeholder="Immatriculation">
                        <label for="immatriculation">Immatriculation</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="phone" name="phone[]"pattern="[0-9]{10}" placeholder="N° Téléphone">
                        <label for="phone">N° Téléphone</label>
                    </div>
                </div>
            </div>
        </div>
        <div id="client-container">
            <div class="client-form row g-3">
                <div class="col-md-2">
                    <div class="form-floating">
                        <select class="form-select" name="type[]" id="type" placeholder="type"
                            aria-label="Floating label select example">
                            @foreach ($clienttypes as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                        <label for="type">Type</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="name[]" placeholder="Nom-prénom">
                        <label for="name">Nom-prénom</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="immatriculation[]"
                            placeholder="Immatriculation">
                        <label for="immatriculation">Immatriculation</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="phone" name="phone[]" pattern="[0-9]{10}" placeholder="N° Téléphone">
                        <label for="phone">N° Téléphone</label>
                    </div>
                </div>
            </div>
        </div>
        <div id="client-container">
            <div class="client-form row g-3">
                <div class="col-md-2">
                    <div class="form-floating">
                        <select class="form-select" name="type[]" id="type" placeholder="type"
                            aria-label="Floating label select example">
                            @foreach ($clienttypes as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                        <label for="type">Type</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="name[]" placeholder="Nom-prénom">
                        <label for="name">Nom-prénom</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="immatriculation[]"
                            placeholder="Immatriculation">
                        <label for="immatriculation">Immatriculation</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="phone" name="phone[]" pattern="[0-9]{10}" placeholder="N° Téléphone">
                        <label for="phone">N° Téléphone</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
        <div id="bus-form-container" class="row"></div>
    </form>
    <script>
        document.querySelectorAll('.client-form').forEach(function(formDiv) {
            const inputs = formDiv.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    formDiv.querySelectorAll('input, select').forEach(function(el) {
                        if (el.id !== 'phone') {
                        el.setAttribute('required', 'required');
                        }
                    });
                });
            });
        });
    </script>
@endsection
