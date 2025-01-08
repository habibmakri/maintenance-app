@extends('base')

@section('title', 'Statistiques')


@section('content')
    <div class="pagetitle">
        <h1>Statistiques Exploataion</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">Direction</a></li>
                <li class="breadcrumb-item">Exploataion</li>
                <li class="breadcrumb-item active">Statistiques</li>
            </ol>
        </nav>
    </div>
@endsection