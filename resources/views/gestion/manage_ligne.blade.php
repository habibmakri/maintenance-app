@extends('base')
@section('title', 'Gestion des lignes')
@section('content')

    <div class="pagetitle">
        <h1>Gestion des Lignes</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Gestion</li>
                <li class="breadcrumb-item active">lignes</li>
            </ol>
            <div class="text-end">
                <a href="{{ route('app.gestion.add_ligne') }}" class="btn btn-primary">
                    Nouveau Ligne
                </a>

            </div>
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

    <table class="table datatable">
        <thead>
            <tr>
                <th>id</th>
                <th>
                    name
                </th>
                <th>station</th>
                <th>terminus</th>
                <th data-type="date" data-format="YYYY/DD/MM">Dernier modification</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lignes as $ligne)
                <tr>
                    <td>{{ $ligne->id }}</td>
                    <td>{{ $ligne->name }}</td>
                    <td>{{ $ligne->station->name }}</td>
                    <td>{{ $ligne->terminus }}</td>
                    <td>{{ $ligne->updated_at }}</td>
                    <td>
                        <i class="bi bi-pencil edit-icon" data-id="{{ $ligne->id }}"
                            style="margin-right:15%;cursor: pointer;" onclick="handleEditClick('{{ $ligne->id }}')"></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        function handleEditClick(id) {
            window.location.href = `/app/manage_ligne/edit_ligne:${id}`;
        }
    </script>
@endsection
