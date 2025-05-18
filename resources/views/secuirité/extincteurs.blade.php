@extends('base')
@section('title', 'Extincteurs')
@section('content')

    <div class="pagetitle">
        <h1>Extincteurs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                {{-- <li class="breadcrumb-item">Gestion</li> --}}
                <li class="breadcrumb-item active">Extincteurs</li>
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


    <table class="table datatable">
        {{-- <thead>
            <tr>
                <th>id</th>
                <th>
                    name
                </th>
                <th>KM-actuelle</th>
                <th>Dernier vidange moteur</th>
                <th>Dernier vidange boite</th>
                <th>Dernier vidange pond</th>
                <th data-type="date" data-format="YYYY/DD/MM">Dernier modification</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($buses as $bus)
                <tr>
                    <td>{{ $bus->id }}</td>
                    <td>{{ $bus->name }}</td>
                    <td>{{ $bus->kmactuelle }}</td>
                    <td>{{ $bus->derniervidange }}</td>
                    <td>{{ $bus->derniervidangeboite }}</td>
                    <td>{{ $bus->derniervidangepond }}</td>
                    <td>{{ $bus->updated_at }}</td>
                    <td>
                        <i class="bi bi-pencil edit-icon" data-id="{{ $bus->id }}"
                            style="margin-right:15%;cursor: pointer;" onclick="handleEditClick('{{ $bus->id }}')"></i>
                    </td>
                </tr>
            @endforeach
        </tbody> --}}
    </table>
    <script>
        function handleEditClick(id) {
            console.log("Editing bus with ID:", id);
            window.location.href = `/app/manage_bus/edit_bus:${id}`;
        }
    </script>
@endsection
