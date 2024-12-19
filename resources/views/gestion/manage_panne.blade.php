@extends('base')
@section('title', 'Gestion des pannes')
@section('content')

    <div class="pagetitle">
        <h1>Gestion des Pannes</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Gestion</li>
                <li class="breadcrumb-item active">pannes</li>
            </ol>
            <div class="text-end">
                <a href="{{ route('app.gestion.add_panne') }}" class="btn btn-primary">
                    Nouveau Panne
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
                <th>type</th>
                <th>updated_at</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pannes as $panne)
                <tr>
                    <td>{{ $panne->id }}</td>
                    <td>{{ $panne->name }}</td>
                    <td>{{ $panne->type }}</td>
                    <td>{{ $panne->updated_at }}</td>
                    <td>
                        <i class="bi bi-trash edit-icon" data-id="{{ $panne->id }}"
                            style="margin-right:15%;cursor: pointer;"
                            onclick="handleDeleteClick('{{ $panne->id }}')"></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        function handleDeleteClick(id) {
            if (confirm('Vous êtes sur?')) {
                fetch(`manage_panne/deletepanne:${id}`, {
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
    </script>
@endsection
