@extends('base')
@section('title', 'Gestion des comptes')
@section('content')


    <div class="pagetitle">
        <h1>Gestion des comptes des utilisateurs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Gestion</li>
                <li class="breadcrumb-item active">comptes</li>
            </ol>
            <div class="text-end">
                <a href="{{ route('app.gestion.add_user') }}" class="btn btn-primary">
                    Nouveau compte
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
                    username
                </th>
                <th>nom & prénom</th>
                <th>email</th>
                <th class="mw-100">autorisation</th>
                <th data-type="date" data-format="YYYY/DD/MM">Dernier modification</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                    <td>{{ $user->email }}</td>
                    <td style="max-width: 200px;    white-space: normal;word-wrap: break-word;">{{ $user->autorisations }}
                    </td>
                    <td>{{ $user->updated_at }}</td>
                    <td>
                        <i class="bi bi-pencil edit-icon" style="margin-right:15%;cursor: pointer;"
                            onclick="handleEditClick('{{ $user->id }}')"></i>
                        <i class="bi bi-trash delete-icon" data-id="{{ $user->id }}" style="cursor: pointer;"
                            onclick="handleDeleteClick('{{ $user->id }}')"></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function handleEditClick(id) {
            window.location.href = `/app/manage_user/edit_user:${id}`;
        }

        function handleDeleteClick(id) {
            if (confirm('Vous êtes sur?')) {
                fetch(`manage_user/deleteuser:${id}`, {
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
