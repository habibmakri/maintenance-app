@extends('base')
@section('title', 'Gestion des pieces')
@section('content')

    <div class="pagetitle">
        <h1>Gestion des Pieces</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Gestion</li>
                <li class="breadcrumb-item active">pieces</li>
            </ol>
            <div class="text-end">
                <a href="{{ route('app.gestion.add_piece') }}" class="btn btn-primary">
                    Nouvelle Piece
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
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pieces as $piece)
                <tr>
                    <td>{{ $piece->id }}</td>
                    <td>{{ $piece->name }}</td>
                    <td>
                        <i class="bi bi-trash edit-icon" data-id="{{ $piece->id }}"
                            style="margin-right:15%;cursor: pointer;"
                            onclick="handleDeleteClick('{{ $piece->id }}')"></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        function handleDeleteClick(id) {
            if (confirm('Vous êtes sur?')) {
                fetch(`manage_piece/deletepiece:${id}`, {
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
