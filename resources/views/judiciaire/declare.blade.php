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


    <form class="row g-3" action="" method="post" dir="rtl" enctype="multipart/form-data">
        @csrf

        <div class="col-md-8">
            <div class="form-floating">
                <input name="date" id="dateInput" type="date" required class="form-control" style="text-align: end;"
                    value="{{ old('date') }}">
                <label for="date">اليوم</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text" class="form-control" required id="floatingNumber" name="number" placeholder="الرقم"
                    value="{{ old('number') }}" maxlength="3">
                <label for="floatingNumber">الرقم</label>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-floating">
                <select class="form-select" required name="bus" required id="bus" placeholder="bus"
                    aria-label="Floating label select example">
                    <option value="" disabled selected>المركبة</option>
                    @foreach ($buses as $bus)
                        <option value="{{ $bus->id }}" @if (old('bus') == $bus->id) selected @endif>
                            {{ $bus->name }}</option>
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
                        <option value="{{ $chauffeur->id }}" @if (old('chauffeur') == $chauffeur->id) selected @endif>
                            {{ $chauffeur->name }}</option>
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
                        <option value="{{ $line->id }}" @if (old('ligne') == $line->id) selected @endif>
                            {{ $line->name }}</option>
                    @endforeach

                </select>
                <label for="ligne">خط الخدمة</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input name="day" id="dateInput" type="date" required class="form-control" style="text-align: end;"
                    value="{{ old('day') }}">
                <label for="day">تاريخ</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input name="time" type="time" required class="form-control" name="hdepart" id="hdepart"
                    value="{{ old('time') }}">
                <label for="hdepart">ساعة</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input name="place" type="text" value="{{ old('place') }}" class="form-control" required
                    id="floatingName" placeholder="Leave a comment here" >
                <label for="lastname">المكان</label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-floating">
                <input name="adverse" type="text" value="{{ old('adverse') }}" class="form-control" required
                    id="floatingName" placeholder="Leave a comment here">
                <label for="adverse">الطرف الآخر</label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-floating">
                <textarea name="description" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"
                    style="height: 150px;">{{ old('description') }}</textarea>
                <label for="description">ظروف الحادث</label>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-floating">
                <textarea name="pertes" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"
                    style="height: 150px;" > {{ old('pertes') }}</textarea>
                <label for="pertes">الخسائر المسجلة</label>
            </div>
        </div>

        <div class="col-md-12">
            <label for="formFile" class="col-sm-2 col-form-label">صور الخسائر</label>
            <input name="photos[]" class="form-control" type="file" id="formFile" accept=".png, .jpg, .jpeg"
                multiple>
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
        document.getElementById("formFile").addEventListener("change", async function(event) {
            const files = event.target.files;
            const compressedImages = [];

            for (const file of files) {
                const compressedFile = await compressImage(file);
                compressedImages.push(compressedFile);
            }

            // Remplace les fichiers originaux par les versions compressées
            const dataTransfer = new DataTransfer();
            compressedImages.forEach(file => dataTransfer.items.add(file));
            event.target.files = dataTransfer.files;
        });

        function compressImage(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = function(event) {
                    const img = new Image();
                    img.src = event.target.result;

                    img.onload = function() {
                        const canvas = document.createElement("canvas");
                        const ctx = canvas.getContext("2d");

                        const maxWidth = 800; // Largeur max
                        const maxHeight = 600; // Hauteur max
                        let width = img.width;
                        let height = img.height;

                        if (width > maxWidth || height > maxHeight) {
                            const scale = Math.min(maxWidth / width, maxHeight / height);
                            width *= scale;
                            height *= scale;
                        }

                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob((blob) => {
                            const compressedFile = new File([blob], file.name, {
                                type: "image/jpeg",
                                lastModified: Date.now()
                            });
                            resolve(compressedFile);
                        }, "image/jpeg", 0.7); // Qualité 70%
                    };
                };
            });
        }
    </script>
@endsection
