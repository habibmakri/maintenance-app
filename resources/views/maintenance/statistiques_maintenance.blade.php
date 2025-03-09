@extends('base')

@section('title', 'Extraire')

@section('content')
    <style>
        /* @font-face {
            font-family: 'lateef';
            src: url('{{ asset('theme/fonts/lateef/Lateef-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        } */
        label {
            inset-inline-end: auto !important;
        }
    </style>
    <div class="pagetitle">
        <h1>Statistiques Maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.main') }}">App</a></li>
                <li class="breadcrumb-item">Maintenance</li>
                <li class="breadcrumb-item active">Statistiques</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist" >
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                type="button" role="tab" aria-controls="home" aria-selected="true">BUS</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Pieces</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Agents</button>
        </li>
    </ul>
    <div class="tab-content pt-2" id="borderedTabContent" style = "font-family: 'Tajwal';">
        <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab" >

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

            
            
        </div>

        <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab" >
            
            
        </div>

    </div>


    <script>
        function handleresoudreclick(declaration, ligneval) {
            const modal_title = document.getElementById('modal_title');
            const declarationIdInput = document.getElementById('fichedeclaration_id');
            const declarationdate = document.getElementById('declaration_date');
            const chauffeur = document.getElementById('chauffeur');
            const ligne = document.getElementById('ligne');
            const time = document.getElementById('time');
            const day = document.getElementById('day');
            const place = document.getElementById('place');
            const description = document.getElementById('description');
            const pertes = document.getElementById('pertes');
            const photos = document.getElementById('photos');
            const caat = document.getElementById('caat');
            const paye = document.getElementById('paye');
            const dateObj = new Date(declaration.date_fiche);
            const formattedDate =
                `${String(dateObj.getDate()).padStart(2, '0')}-${String(dateObj.getMonth() + 1).padStart(2, '0')}-${dateObj.getFullYear()}`;
            const bus = document.getElementById('bus');
            modal_title.innerHTML = '';
            declarationdate.innerHTML = '';
            bus.innerHTML = '';
            chauffeur.innerHTML = '';
            ligne.innerHTML = '';
            time.innerHTML = '';
            day.innerHTML = '';
            place.innerHTML = '';
            caat.innerHTML = '';
            paye.innerHTML = '';
            description.innerHTML = '';
            pertes.innerHTML = '';
            photos.innerHTML = '';
            declarationphotos = '';
            modal_title.innerHTML = declaration.bus.name + ' - ' + declaration.chauffeur.fr_name + ' le ' + declaration
                .time_day + ' signaler le ' +
                declaration.date_fiche;
            declarationIdInput.value = declaration.id;
            declarationdate.innerHTML = formattedDate;
            bus.innerHTML = declaration.bus.name;
            chauffeur.innerHTML = declaration.chauffeur.name;
            if(ligneval){
                ligne.innerHTML = ligneval.name;
            }else{
                ligne.innerHTML = "خارج الخدمة";
            }
            time.innerHTML = declaration.time_day.split(" ")[1];
            day.innerHTML = declaration.time_day;
            place.innerHTML = declaration.place;
            description.innerHTML = declaration.description;
            pertes.innerHTML = declaration.pertes;
            photos.innerHTML = ``;
            declarationphotos = JSON.parse(declaration.photos);
            if (declaration.caat) {
                caat.innerHTML = `ثم التصريح في ${declaration.date_caat}`
            } else {
                caat.innerHTML = `لم يتم التصريح بعد`
            }
            if (declaration.paye) {
                paye.innerHTML = `ثم تلقي ${declaration.paye_montant}دج  في ${declaration.date_paye}`
            } else {
                paye.innerHTML = `لم يتم تلقي المصاريف بعد`
            }

            if (declarationphotos && Array.isArray(declarationphotos) && declarationphotos.length > 0) {
                let indicators = '';
                let slides = '';

                declarationphotos.forEach((photo, index) => {
                    indicators += `
            <button type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide-to="${index}" class="${index === 0 ? 'active' : ''}"
            aria-label="Slide ${index + 1}"></button>
        `;

                    slides += `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="https://direction.etus22.dz/${photo}" class="d-block w-100" alt="Image ${index + 1}">
            </div>
        `;
                });

                photos.innerHTML = `
        <h5 style="font-family: 'Tajwal';font-weight: bold;">الصور: <br><span id="pertes"></span></h5>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="width: 65%; justify-self: center;">
            <div class="carousel-indicators">
                ${indicators}
            </div>
            <div class="carousel-inner">
                ${slides}
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    `;
            }

        }

        function handleDeleteClick(id) {
            if (confirm('Vous êtes sur?')) {
                fetch(`maintenance/deletefichepanne:${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Opération réussie!');
                            location.reload();
                        } else {
                            alert('Opération échouée!');
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }
        }
    </script>
@endsection
