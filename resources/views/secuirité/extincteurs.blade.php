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
        <thead>
            <tr>
                <th>N°</th>
                <th>
                    Reference
                </th>
                <th>Type</th>
                <th>Affectation</th>
                <th>Date Recahrge</th>
                <th>Date d'expiration</th>
                <th>Reste</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($extincteurs as $extincteur)
                <tr>
                    <td>{{ $extincteur->id }}</td>
                    <td>{{ $extincteur->reference }}</td>
                    <td>{{ $extincteur->type }}</td>
                    <td>{{ $extincteur->affectation }}</td>
                    <td>{{ $extincteur->date_recharge }}</td>
                    <td>{{ $extincteur->date_expiration }}</td>
                    @php
                        $rechargeDate = \Carbon\Carbon::parse($extincteur->date_recharge);
                        $expirationDate = \Carbon\Carbon::parse($extincteur->date_expiration);
                        $differenceInDays = $expirationDate->diffInDays($rechargeDate, false);
                        if ($differenceInDays < 0) {
                            $cssClass = 'text-danger fw-bold'; 
                        } elseif ($differenceInDays < 15) {
                            $cssClass = 'text-warning fw-bold';
                        } else {
                            $cssClass = ''; 
                        }
                    @endphp
                    <td class="{{ $cssClass }}">
                        {{ $differenceInDays }} Jours
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        function handleEditClick(id) {
            console.log("Editing bus with ID:", id);
            window.location.href = `/app/manage_bus/edit_bus:${id}`;
        }
    </script>
@endsection
