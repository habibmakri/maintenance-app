@extends('base')
@section('title', 'Extincteurs')
@section('content')

    <div class="pagetitle">
        <h1>Taxis</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Formaion</li>
                <li class="breadcrumb-item active">Taxis</li>
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


    <table class="table datatable mt-1" dir="rtl" style="text-align: right;">
        <thead dir="rtl">
            <tr>
                <th style="text-align: right;">الرقم</th>
                <th style="text-align: right;">
                    الأسم
                </th>
                <th style="text-align: right;">اللقب</th>
                <th style="text-align: right;">تاريخ ومكان الميلاد</th>
                <th style="text-align: right;">العنوان</th>
                <th style="text-align: right;">رقم الهاتف</th>
                <th style="text-align: right;">بلديةالإستغلال</th>
            </tr>
        </thead>
        <tbody dir="rtl">
            @foreach ($taxis as $taxi)
                <tr>
                    <td style="text-align: right;">{{ $taxi->id }}</td>
                    <td style="text-align: right;">{{ $taxi->nom_ar }}</td>
                    <td style="text-align: right;">{{ $taxi->prenom_ar }}</td>
                    <td style="text-align: right;">{{ $taxi->birthdate .'  '. $taxi->birthplace }}</td>
                    <td style="text-align: right;">{{ $taxi->adresse }}</td>
                    <td style="text-align: right;">{{ $taxi->phone  }}</td>
                    <td style="text-align: right;">{{ $taxi->comune_exploi  }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
