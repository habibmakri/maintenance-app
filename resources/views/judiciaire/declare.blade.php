@extends('base')
@section('title', 'Gestion des comptes')
@section('content')
    <style>
        label {
            inset-inline-end: auto !important;
        }
    </style>
    <div class="pagetitle">
        <h1>Déclaration d'Accident</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Judiciare</li>
                <li class="breadcrumb-item ">Déclaration</li>
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


    <form class="row g-3" action="" method="post" dir="rtl">
        @csrf

        <div class="col-md-8">
            <div class="form-floating">
                <input name="date" id="dateInput" type="date"
                    required class="form-control"
                    style="text-align: end;">
                <label for="date">اليوم</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control" required="" id="floatingName" name="name"
                    placeholder="nom">
                <label for="name">الرقم</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" required name="bus" required id="bus" placeholder="bus"
                    aria-label="Floating label select example">
                    <option value="" disabled selected>المركبة</option>
                    @foreach ($buses as $bus)
                        <option value="{{ $bus->id }}">{{ $bus->name }}</option>
                    @endforeach

                </select>
                <label for="bus">المركبة</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" required name="chauffeur" required id="chauffeur" placeholder="chauffeur"
                    aria-label="Floating label select example">
                    <option value="" disabled selected>السائق</option>
                    @foreach ($chauffeurs as $chauffeur)
                        <option value="{{ $chauffeur->id }}">{{ $chauffeur->name }}</option>
                    @endforeach

                </select>
                <label for="chauffeur">السائق</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" required name="ligne" required id="ligne" placeholder="ligne"
                    aria-label="Floating label select example">
                    <option value="" disabled selected>الخط</option>
                    @foreach ($lines as $line)
                        <option value="{{ $line->id }}">{{ $line->name }}</option>
                    @endforeach

                </select>
                <label for="ligne">خط الخدمة</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input name="day" id="dateInput" type="date"
                     required class="form-control"
                    style="text-align: end;">
                <label for="day">تاريخ</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input name="time" type="time" required class="form-control" name="hdepart" id="hdepart">
                <label for="hdepart">ساعة</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input name="place" type="text" class="form-control" required id="floatingName" name="lastname" placeholder="prénom">
                <label for="lastname">المكان</label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-floating">
                <textarea name="description" class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 150px;"></textarea>
                <label for="description">ظروف الحادث</label>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-floating">
                <textarea name="pertes" class="form-control"  placeholder="Leave a comment here" id="floatingTextarea" style="height: 150px;"></textarea>
                <label for="pertes">الخسائر المسجلة</label>
            </div>
        </div>

        <div class="col-md-12">
            <label  for="formFile" class="col-sm-2 col-form-label">صور الخسائر</label>
            <input name="photos[]" class="form-control" type="file" id="formFile" accept=".png, .jpg, .jpeg" multiple>
        </div>


        <div class="text-end">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
        <div id="bus-form-container" class="row"></div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("dateInput").value = new Date().toISOString().split('T')[0];
        });
        
    </script>
@endsection
